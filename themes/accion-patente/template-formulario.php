<?php /* Template Name: Formulario */ ?>

<?php
if ( isset($_POST['project_form']) && $_POST['project_form'] == 'go' ):

  // PROJECT GENERAL
  $project = new Project('227_projects', array(
    'reg_date'      => date('Y-m-d H:i:s'),
    'name'          => post('theTitle', 'text'),
    'municipality'  => post('validaMunicipalidad', 'text'),
    'state'         => 'publish',
    'category'      => post('theCategory', 'text'),
    'abstract'      => post('theExcerpt', 'text'),
    'background'    => post('theMotive', 'text'),
    'description'   => post('theContent', 'text'),
    'plan_desc'     => post('aboutPlan', 'text'),
    'place'         => post('thePlace', 'text'),
    'budget_gen'    => post('thePrice', 'int'),
    'budget_desc'   => post('aboutPrice', 'text'),
    'start_date'    => post('dateInicio', 'date'),
    'end_date'      => post('dateTermino', 'date'),
    'opening_date'  => post('dateEntrega', 'date'),
    'delivery_date' => post('dateEntrega', 'date'),
  ));

  // POST META
  if ($project->post) {
    add_post_meta($project->post, 'validador_nombre', $_POST['validaNombre']);
    add_post_meta($project->post, 'validador_rut', $_POST['validaRut']);
  }
  else{
    update_post_meta($_POST['project_post_id'], 'validador_nombre', $_POST['validaNombre']);
    update_post_meta($_POST['project_post_id'], 'validador_rut', $_POST['validaRut']);
  }

  // PROJECT MEDIA
  $project_media = ['projectFeaturedImage', 'projectActualImage', 'projectResultImage', 'managerImage1', 'managerImage2', 'managerImage3'];
  foreach ($project_media as $name) {
    $image_id = upload_image($name);
    $image_id = $image_id? $image_id : post($name.'_wpmedia', 'int');
    if ($image_id != 0) {
      $project->write('227_proj_media', array(
        'wp_media_id' => $image_id,
        'type'        => $name
      ));
      if ($name == 'projectFeaturedImage') set_post_thumbnail($project->post, $image_id);
    }
  }

  // PROJECT BUDGET
  foreach ($_POST['partialBudgetlName'] as $i => $name) {
    $project->write('227_proj_budgets', array(
      'name'    => htmlentities($name, ENT_QUOTES),
      'value'   => post('partialBudgetlValue', 'int', $i),
      'justify' => post('partialBudgetlAbout', 'text', $i)
    ));
  }

  // PROJECT STEPS
  foreach ($_POST['projectStepName'] as $i => $name) {
    $project->write('227_proj_phases', array(
      'name'        => htmlentities($name, ENT_QUOTES),
      'start_date'  => post('projectStepStart', 'date', $i),
      'end_date'    => post('projectStepEnd', 'date', $i),
      'description' => post('projectStepAbout', 'text', $i)
    ));
  }

  // PROJECT MANAGERS
  foreach ($_POST['managerName'] as $i => $name) {
    $project->write('227_proj_managers', array(
      'name'     => htmlentities($name, ENT_QUOTES),
      'type'     => post('managerType', 'text', $i),
      'charge'   => post('managerCharge', 'text', $i),
      'unit'     => post('managerUnit', 'text', $i),
      'business' => post('managerBusiness', 'text', $i),
      'email'    => post('managerEmail', 'text', $i)
    ));
  }

  if($project->right){
    header('Location: '.get_permalink(25));
    //echo '<pre>'.print_r($_POST).print_r($_FILES).'</pre>';
    //echo '<body><pre>'.print_r($_FILES).'</pre>';
  }
  else{
      echo '<h1 class="text-center">Algo salio mal, lo sentimos</h1>';
  }
else:
?>

<?php get_header();?>

<script>
//Nombre Proyecto:

var $ = jQuery;

function countTitle(val){
  console.log(this);
  var limit = 100;
  if (val.length > limit){
  $("#theTitle").val(val.substring(0, limit-1));
  val.length = limit;
  }
  $("#title").html((limit)-(val.length));
}

//Resumen
function countExcerpt(val){
var limit = 300;
if ( val.length > limit ){
$("#theExcerpt").val(val.substring(0, limit-1));
val.length = limit;
}
$("#excerpt").html((limit)-(val.length));
}

//Descripción
function countContent(val){
var limit = 650;
if ( val.length > limit ){
$("#theContent").val(val.substring(0, limit-1));
val.length = limit;
}
$("#content").html((limit)-(val.length));
}

//Antecedentes
function countMotive(val){
var limit = 650;
if ( val.length > limit ){
$("#theMotive").val(val.substring(0, limit-1));
val.length = limit;
}
$("#motive").html((limit)-(val.length));
}

jQuery(function($){
  $(".add_li").click(function(e){
    e.preventDefault();
    var rel = '#' + $(this).attr('rel');
    $(rel)
      .children()
      .last()
      .clone()
      .appendTo(rel)
      .find('label, input, select, textarea')
      .each(function(){
        increase.call(this, 'id');
        increase.call(this, 'for');
      });

    $(rel).find('.date').datetimepicker({
      'format': 'YYYY-MM-DD',
      'useCurrent': false,
      'allowInputToggle': true    
});
  });
  $(".del_li").click(function(e){
    e.preventDefault();
    $(this).closest('li').remove();
  });
  function increase(attr){
    var num = null;
    $(this).attr(attr, function(){
      var val = $(this).attr(attr);
      if (val) {
        num = val.slice(-1)*1+1;
        return val.slice(0,-1) + num;
      }
    });
    return num;
  }
});

$("#remove_li").click(function(){
  $("li:last").remove();
});

</script>



<section class="container">
<form action="." method="post" enctype="multipart/form-data">
<div class="row">
      <div class="col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1">

      <!--Primera Parte: Antecedentes del Proyecto-->
      <div class="row franja">
      <div class="col-xs-2 col-md-1">
      <h3 class="nro">1</h3>
      </div>
      <div class="col-xs-10 col-md-11">
      <h3>Antecedentes del Proyecto</h3>
        <div class="form-group">
          <label for="theTitle">Nombre Proyecto</label>
          <input type="text" class="form-control" name="theTitle" id="theTitle" placeholder="Nombre del proyecto en máximo de 100 caracteres" onKeyUp="countTitle(this.value)" required>
          <p class="help-block"><span id="title"></span></p>
        </div>
        <div class="form-group">
          <label for="theCategory">Categoría</label>
          <select class="form-control" name="theCategory"  id="theCategory" required>
            <option>Escoger categoría…</option>
            <option>Cultura</option>
            <option>Educación</option>
            <option>Deporte</option>
            <option>Medio ambiente, aseo y ornato</option>
            <option>Salud</option>
            <option>Seguridad ciudadana</option>
            <option>Tránsito</option>
            <option>Turismo</option>
          </select>
        </div>
        <div class="form-group">
          <label for="theContent">Resumen</label>
          <textarea type="text" class="form-control" name="theExcerpt" id="theExcerpt" placeholder="Resumen del proyecto en máximo de 300 caracteres" onKeyUp="countExcerpt(this.value)" required></textarea>
          <p class="help-block"><span id="excerpt"></span></p>
        </div>
        <div class="form-group">
          <label for="theMotive">Antecedentes</label>
          <textarea type="text" class="form-control" name="theMotive" id="theMotive" placeholder="Problema que el proyecto solucionará en máximo de 650 caracteres" onKeyUp="countMotive(this.value)" required></textarea>
          <p class="help-block"><span id="motive"></span></p>
        </div>
        <div class="form-group">
          <label for="theContent">Descripción</label>
          <textarea type="text" class="form-control" name="theContent" id="theContent" placeholder="Descripción del proyecto en máximo de 650 caracteres" onKeyUp="countContent(this.value)" required></textarea>
          <p class="help-block"><span id="content"></span></p>
        </div>
        <div class="form-group">
          <label for="thePlace">Ubicación</label>
          <input type="text" class="form-control" name="thePlace"  id="thePlace" placeholder="Dirección" onKeyUp="countPlace(this.value)" required>
          <p class="help-block"><span id="place"></span></p>
        </div>


    <label>Imágenes</label>
  <div class="alert alert-recuadro">
    <p>Se recomienda utilizar imágenes JPG, de un peso mayor que 20 Kb y menor que 999 Kb. En la Imagen del proyecto debe usarse un JPG de tamaño 700 pixeles de ancho y 400 pixeles de alto. En las imágenes de referencia se recomienda mantener el ancho de 700 pixeles.</p>
    <div class="form-group row">
    <div class="col-sm-6">
      <label for="projectFeaturedImage" class="imagen">Imagen del proyecto</label>
    </div>
    <div class="col-sm-6">
      <input type="file" id="projectFeaturedImage" name="projectFeaturedImage">
    </div>
    </div>
    <div class="form-group row">
    <div class="col-sm-6">
      <label for="projectActualImage" class="imagen">Imagen de referencia: Estado actual de la situación a intervenir</label>
    </div>
    <div class="col-sm-6">
      <input type="file" id="projectActualImage" name="projectActualImage">
    </div>
    </div>
    <div class="form-group row">
    <div class="col-sm-6">
      <label for="projectResultImage" class="imagen">Imagen de referencia: Resultado esperado</label>
    </div>
    <div class="col-sm-6">
      <input type="file" id="projectResultImage" name="projectResultImage">
    </div>
    </div>
  </div><!--alert-->

  </div>
  </div>

      <!--Segunda Parte: Presupuesto-->
      <div class="row franjas">
      <div class="col-xs-2 col-md-1">
      <h3 class="nro">2</h3>
      </div>
      <div class="col-xs-10 col-md-11">

      <h3>Presupuesto</h3>

        <div class="form-group">
          <label for="thePrice">Valor general</label>
          <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control" id="thePrice" name="thePrice" placeholder="Ingrese valor bruto total en pesos">
            </div>
        </div>

        <div class="form-group">
          <label for="aboutPrice">Descripción del presupuesto</label>
          <textarea type="text" class="form-control" name="aboutPrice" id="aboutPrice" placeholder="Detalles sobre el precio y/o distribución general de los ítems"></textarea>
        </div>


<label>Valores parciales</label>

<p class="indicacion">Se recomienda indicar, al menos, 3 ítem que al sumarse den por resultado el valor general. En cada ítem debe ingresar un nombre, valor parcial y justificación breve. Los valores deben indicarse sin puntos ni comas.</p>

<!--desde aquí esto debería repetirse con jQuery-->

<ol id="partials_budgets">
  <li class="alert alert-recuadro">
  <!--MARIO, ACÁ PUSE EL COSO PARA ELIMINAR-->
  <a class="eliminar_budget del_li" href="#"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> Eliminar ítem</a>
  <!--EL COSO COSO-->
    <label class="detallista">Detalle del ítem</label>
    <div class="form-group row">
      <div class="col-sm-2">
        <label class="control-label" for="nombreItem1">Nombre</label>
      </div>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="nombreItem1" name="partialBudgetlName[]" placeholder="Ingrese nombre del ítem">
      </div>
    </div><!--row-->

    <div class="form-group row">
      <div class="col-sm-2">
        <label class="control-label" for="valorItem1">Valor</label>
      </div>
      <div class="col-sm-10">
        <div class="input-group">
          <div class="input-group-addon">$</div>
          <input type="text" class="form-control" id="valorItem1" name="partialBudgetlValue[]" placeholder="Ingrese valor neto total en pesos">
        </div>
      </div>
    </div><!--row-->

    <div class="form-group row">
      <div class="col-sm-2">
        <label class="control-label" for="aboutItem1">Justificación</label>
      </div>
      <div class="col-sm-10">
        <textarea type="text" class="form-control" name="partialBudgetlAbout[]" id="aboutItem1" placeholder="Justificación en máximo de 200 caracteres" onKeyUp="countExcerpt(this.value)"></textarea>
      </div>
    </div><!--row-->
  </li><!--alert-->
</ol>
<!--hasta aquí esto debería repetirse con jQuery-->
<!--esto lo controlaría jQuery-->
  <button class="btn btn-info add_li pull-right" rel="partials_budgets"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Agregar nuevo ítem</button>
<!--eso lo controlaría-->
</div>
</div>

<!--Tercera Parte: Plazos-->
<div class="row franjas">
  <div class="col-xs-2 col-md-1">
    <h3 class="nro">3</h3>
  </div>
  <div class="col-xs-10 col-md-11">
    <h3>Planificación</h3>

<p class="indicacion">Se recomienda una cuidadosa revisión de las fechas que se ingresen a continuación.</p>

<div class="form-group">
      <label for="theInicio">Fecha de Inicio</label>
      <div class="input-group date">
        <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
        <input class="form-control" id="dateInicio" name="dateInicio" placeholder="MM/DD/YYYY" type="text"/>
      </div>
    </div>

    <div class="form-group">
      <label for="theTermino">Fecha de Entrega</label>
      <div class="input-group date">
        <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
        <input class="form-control" id="dateTermino" name="dateTermino" placeholder="MM/DD/YYYY" type="text"/>
      </div>
    </div>

    <div class="form-group">
      <label for="theTermino">Fecha de Inaguración</label>
        <div class="input-group date">
        <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
        <input class="form-control" id="dateEntrega" name="dateEntrega" placeholder="MM/DD/YYYY" type="text"/>
      </div>
    </div>
    
    <div class="form-group">
      <label for="aboutPlan">Descripción de planificación</label>
      <textarea type="text" class="form-control" id="aboutPlan" name="aboutPlan" placeholder="Detalles sobre la planificación y/o distribución general de los tiempos"></textarea>
    </div>


    <label>Etapas de ejecución</label>
<p class="indicacion">Se recomienda indicar, al menos, 3 etapas de ejecución. En cada etapa debe ingresar un nombre, fecha de inicio, fecha de término y descripción breve de los objetivos que deben cumplirse en la respectiva etapa.</p>
    <!--desde aquí esto debería repetirse con jQuery-->
    <ol id="project_steps">
      <li class="alert alert-recuadro">

  <!--MARIO, ACÁ PUSE EL COSO PARA ELIMINAR-->
  <a class="eliminar_phase del_li" href="#"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> Eliminar etapa</a>
  <!--EL COSO COSO-->
    <label class="detallista">Detalle de la etapa</label>

        <div class="form-group row">
          <div class="col-sm-2">
            <label class="control-label" for="stepName1">Nombre</label>
          </div>
          <div class="col-sm-10">
            <input class="form-control" id="stepName1" name="projectStepName[]" type="text"/>
          </div>
        </div>
        
        <div class="form-group row">
          <div class="col-sm-2">
            <label class="control-label" for="stepStart1">Inicio</label>
          </div>
          <div class="col-sm-10">
            <div class="input-group date">
              <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
              <input class="form-control" id="stepStart1" name="projectStepStart[]" placeholder="MM/DD/YYYY" type="text"/>
            </div>
          </div>
        </div><!--row-->

        <div class="form-group row">
          <div class="col-sm-2">
            <label class="control-label" for="stepEnd1">Término</label>
          </div>
          <div class="col-sm-10">
            <div class="input-group date">
              <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
              <input class="form-control" id="stepEnd1" name="projectStepEnd[]" placeholder="MM/DD/YYYY" type="text"/>
            </div>
          </div>
        </div><!--row-->
        <div class="form-group row">
          <div class="col-sm-2">
            <label class="control-label" for="stepAbout1">Descripción</label>
          </div>
          <div class="col-sm-10">
            <textarea type="text" class="form-control" id="stepAbout1" name="projectStepAbout[]" placeholder="Descripción de los objetivos de la etapa"></textarea>
          </div>
        </div><!--row-->
      </li><!--alert-->
    </ol>
    <!--hasta aquí esto debería repetirse con jQuery-->
    <!--esto lo controlaría jQuery-->
      <button class="btn btn-info add_li pull-right" rel="project_steps"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Agregar nueva etapa</button>
    <!--eso lo controlaría-->
  </div>
</div>

      <!--cuarto-->
      <div class="row franjas">
      <div class="col-xs-2 col-md-1">
      <h3 class="nro">4</h3>
      </div>
      <div class="col-xs-10 col-md-11">
      <h3>Responsables</h3>

      <h5>Funcionario Municipal</h5>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Nombre</div>
          <input type="hidden" name="managerType[]" value="funcionario">
          <input type="text" class="form-control" name="managerName[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Cargo</div>
          <input type="text" class="form-control" name="managerCharge[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Unidad</div>
          <input type="text" class="form-control" name="managerUnit[]">
          <input type="hidden" name="managerBusiness[]" value="municipality">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">E-Mail</div>
          <input type="email" class="form-control" name="managerEmail[]">
        </div>
      </div>

<div class="alert alert-recuadro">
      <div class="form-group row">
        <div class="col-sm-6">
          <label for="managerImage1" class="imagen">Subir imagen de perfil</label>
        </div>
        <div class="col-sm-6">
          <input type="file" id="managerImage1" name="managerImage1">
        </div>
      </div>
</div>

    <h5>Profesional</h5>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Nombre</div>
          <input type="hidden" name="managerType[]" value="ejecutor">
          <input type="text" class="form-control" name="managerName[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Cargo</div>
          <input type="text" class="form-control" name="managerCharge[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Empresa</div>
          <input type="hidden" name="managerUnit[]" value="External">
          <input type="text" class="form-control" name="managerBusiness[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">E Mail</div>
          <input type="email" class="form-control" name="managerEmail[]">
        </div>
      </div>

<div class="alert alert-recuadro">
      <div class="form-group row">
      <div class="col-sm-6">
        <label for="managerImage3" class="imagen">Subir imagen de perfil</label>
      </div>
      <div class="col-sm-6">
        <input type="file" id="managerImage3" name="managerImage3">
      </div>
      </div>
</div>


      <h5>Ejecutante</h5>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Nombre</div>
          <input type="hidden" name="managerType[]" value="profesional">
          <input type="text" class="form-control" name="managerName[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Cargo</div>
          <input type="text" class="form-control" name="managerCharge[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">Empresa</div>
          <input type="hidden" name="managerUnit[]" value="External">
          <input type="text" class="form-control" name="managerBusiness[]">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <div class="input-group-addon ancho-minimo">E Mail</div>
          <input type="email" class="form-control" name="managerEmail[]">
        </div>
      </div>

<div class="alert alert-recuadro">
      <div class="form-group row">
      <div class="col-sm-6">
        <label for="managerImage2" class="imagen">Subir imagen de perfil</label>
      </div>
      <div class="col-sm-6">
        <input type="file" id="managerImage2" name="managerImage2">
      </div>
      </div>
</div>

  

      </div>
      </div>

      <div class="row franjalast">
      <div class="col-xs-2 col-md-1">
      <h3 class="nro">5</h3>
      </div>
      <div class="col-xs-10 col-md-11 final">
        <input type="hidden" name="project_form" value="go">
        <?php
        if ( isset($_POST['project_id']) ){
          echo '<input type="hidden" name="project_id" value="'.$_POST['project_id'].'">';
        }
        if ( isset($_POST['project_post_id']) ){
          echo '<input type="hidden" name="project_post_id" value="'.$_POST['project_post_id'].'">';
        }
        ?>
        <button class="btn btn-warning btn-lg" id="to_check">VALIDAR</button>
        <div id="check" class="hidden">
          <h3><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Validación</h3>
          <p>Yo 
          <input type="text" id="validaNombre" name="validaNombre">, RUT 
          <input type="text" id="validaRut" name="validaRut">, funcionario de la Municipalidad de 
          <input type="text" id="validaMunicipalidad" name="validaMunicipalidad">, certifico y doy fe de que los datos contenidos en esta página han sido revisados en detalle el <?php echo (date_i18n('l j \d\e F \d\e\l Y'));?>, y pueden ser ingresados, bajo mi responsabilidad, a una base de datos de consulta pública.</p>
          <input type="submit" class="btn btn-success btn-lg pull-right" value="ACEPTAR">
        </div>
      </form>

      </div>
    </div>
  </div>

  </div>
</div>
<?php
if ( isset($_POST['project_id']) ){
  $project = get_the_project($_POST['project_id']);
  foreach ($project->media as $media) {
    $media->src = wp_get_attachment_image_src($media->wp_media_id, 'full');
  }
  ?>
  <script type="text/javascript">
    var project = <?php echo json_encode($project); ?>;
    var decode = (function(){
      var element = document.createElement('div');
      return function(str){
        if(str && typeof str === 'string') {
          str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
          str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
          element.innerHTML = str;
          str = element.textContent;
          element.textContent = '';
        }
        return str;
      };
    })();
    jQuery(function($){
      console.log(project);
      $('#theTitle').val(decode(project.name));
      $('#theCategory').val(decode(project.category));
      $('#theExcerpt').val(decode(project.abstract));
      $('#theMotive').val(decode(project.background));
      $('#theContent').val(decode(project.description));
      $('#aboutPlan').val(decode(project.plan_desc));
      $('#thePlace').val(decode(project.place));
      $('#thePrice').val(decode(project.budget_gen));
      $('#aboutPrice').val(decode(project.budget_desc));
      $('#dateInicio').val(decode(project.start_date));
      $('#dateTermino').val(decode(project.end_date));
      $('#dateEntrega').val(decode(project.delivery_date));
      for(var i = 1; i < project.budgets.length; i++){
        $('.add_li[rel="partials_budgets"]').trigger('click');
      }
      project.budgets.reverse().map(function(d,i){
        $('#nombreItem'+(i+1)).val(decode(d.name));
        $('#valorItem'+(i+1)).val(decode(d.value));
        $('#aboutItem'+(i+1)).val(decode(d.justify));
      });
      for(var i = 1; i < project.phases.length; i++){
        $('.add_li[rel="project_steps"]').trigger('click');
      }
      project.phases.reverse().map(function(d,i){
        $('#stepName'+(i+1)).val(decode(d.name));
        $('#stepStart'+(i+1)).val(decode(d.start_date));
        $('#stepEnd'+(i+1)).val(decode(d.end_date));
        $('#stepAbout'+(i+1)).val(decode(d.description));
      });
      project.managers.reverse().map(function(d,i){
        $('input[name="managerType[]"]').eq(i).val(decode(d.type));
        $('input[name="managerName[]"]').eq(i).val(decode(d.name));
        $('input[name="managerCharge[]"]').eq(i).val(decode(d.charge));
        $('input[name="managerUnit[]"]').eq(i).val(decode(d.unit));
        $('input[name="managerBusiness[]"]').eq(i).val(decode(d.business));
        $('input[name="managerEmail[]"]').eq(i).val(decode(d.email));
      });
      project.media.map(function(d,i){
        $('#'+d.type)
          .after('<a href="'+d.src[0]+'" target="_blank">Imagen</a>')
          .after('<input name="'+d.type+'_wpmedia" value="'+d.wp_media_id+'" type="hidden">');
      });
      $(".del_li").click(function(e){
        e.preventDefault();
        $(this).closest('li').remove();
      });
      /*
      $('textarea').each(function(){
        var that = this;
        var name = $(this).attr('name');
        var value = $(this).val();
        var input = $('<input type="hidden" name="'+name+'" value="'+value+'">');
        $(this)
          .attr('name', name+'_view')
          .keyup(function(){
            input.val($(that).val());
          })
          .after(input)
          .siblings('label')
          .attr('for', name+'_view');
      });
      */
    });
  </script>
  <?php
}
?>
<script>
$('#to_check').click(function(e){
  e.preventDefault();
  $('#check').removeClass('hidden');
});
$(function(){
  $('.date').datetimepicker({
    'format': 'YYYY-MM-DD',
    'useCurrent': false,
    'allowInputToggle': true
  });
});
</script>
</section><!--container-->
<?php endif; ?>
<?php get_footer();?>
