<footer>
<div class="container">
<div class="row">

<div class="col-sm-3">
<dl>
<dt><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> Municipalidad de Petorca</dt>
<dd><a href="https://goo.gl/maps/JjAz6jnmVv32" target="_blank">Silva 225, Petorca,<br />
Provincia de Petorca,<br />
Región de Valparaíso,<br />
Chile</a></dd>
</dl>
</div>

<div class="col-sm-3">
<dl>
<dt><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Vínculos</dt>
<dd><a href="http://www.municipalidadpetorca.cl/" target="_blank">Sitio Web I. Municipalidad de Petorca</a></dd>
<dd><a href="http://www.municipalidadpetorca.cl/NV1/?page_id=83" target="_blank">Teléfonos I. Municipalidad de Petorca</a></dd>
<dd><a href="https://www.facebook.com/places/Cosas-para-hacer-en-Petorca-Valparaiso-Chile/115834111763080/" target="_blank">Petorca en Facebook</a></dd>
<dd><a href="https://www.sem.gob.cl/" target="_blank">Portal de Servicios Municipales</a></dd>
</dl>
</div>

<div class="col-sm-6">
<p>Los contenidos en este sitio son publicados bajo una <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">licencia de Creative Commons Reconocimiento-NoComercial-CompartirIgual 4.0 Internacional</a>.</p>
<p><a href="<?php bloginfo('url');?>">PORTADA</a>  
<?php
global $user_ID;
$roles = get_userdata($user_ID)->roles;
$has_permission = $user_ID ? in_array('municipality', $roles) || in_array('administrator', $roles) : false;
if (is_page(25)){ // Gestión de proyectos
  echo '<span>|</span> <a href="'.get_home_url().'">SALIR DE GESTIÓN DE PROYECTOS</a> ';
}
else{
  if ($has_permission) echo '<span>|</span> <a href="'.get_permalink(25).'">GESTIÓN DE PROYECTOS</a> ';
  else{
    echo '<span>|</span> <a href="#" data-toggle="modal" data-target="#myLogin">GESTIÓN DE PROYECTOS</a> ';
  }
}
?>
</p>
</div>
</div>
</div>
</footer>
<!-- Desde acá el modal para login -->
<div class="modal fade" id="myLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog modal-sm">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Acceder a la Gestión de Proyectos</h4>
      </div><!--/modal-header-->

      <div class="modal-body">
        <?php if ($_SESSION['login_error']): ?>
          <div class="alert alert-danger">
            <h4><?php echo $_SESSION['login_error']; ?></h4>
          </div>
        <?php endif; ?>
        <form class="form-signin" action="<?php bloginfo('url')?>/gestion-de-proyectos" method="post">
        <input type="hidden" name="login" value="1">
        <input type="hidden" name="rememberme" value="1">
        <input type="text" name="log" class="form-control" placeholder="Usuario" required autofocus>
        <input name="pwd" type="password" class="form-control" placeholder="Password" required>
        <input class="btn btn-md btn-primary btn-block" type="submit" value="Acceder">
        </form>
      </div><!--/modal-body-->

    </div><!--/modal-content-->

  </div><!--/modal-dialog-->

</div><!--hasta acá el modal de login-->

<?php wp_footer(); ?>
<?php if ($_SESSION['login_error']) { ?>
<script>
jQuery(function($){
  $('#myLogin').modal('show');
});
</script>
<?php $_SESSION['login_error'] = ''; ?>
<?php } ?>
</body>
</html>