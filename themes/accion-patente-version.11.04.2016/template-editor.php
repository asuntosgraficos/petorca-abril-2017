<?php /* Template Name: Editor */ ?>

<?php
session_start();
global $user_ID;
$login = $user_ID ? false : wp_signon();
if ($login && is_wp_error($login)){
  $_SESSION['login_error'] = 'Su nombre o contraseña es invalido.';
  header('Location: '.get_bloginfo('url'));
}
else {
  $id = $login ? $login->ID : $user_ID;
  $roles = get_userdata($id)->roles;
  $has_permission = in_array('municipality', $roles) || in_array('administrator', $roles);
  if ($has_permission != '1') {
    $_SESSION['login_error'] = 'Usted no posee permisos para acceder a esta sección.';
    header('Location: '.get_bloginfo('url'));
  }
  else{
    $_SESSION['login_error'] = '';
  }
}
if ($_POST['del_project_post_id']) wp_trash_post(intval($_POST['del_project_post_id']));
if ($_POST['del_project_id']){
  global $wpdb;
  $wpdb->update('277_projects', array('state' => 'trash'), array('id' => $_POST['del_project_id']));
}
?>

<?php get_header();?>

<section class="container">

<div class="row">
      <div class="col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1">


<h3>Editar Proyectos</h3>

      <table class="table table-hover">
      <thead>
      <tr>
      <th>Nombre del Proyecto</th>
      <th>Fecha de Envío</th>
      <th>Validador</th>
      <th>Eliminar</th>
      </tr>
      </thead>

      <tbody>

      <?php
        $projects = get_posts(array(
          'post_type'      => 'projects',
          'posts_per_page' => -1
        ));

        foreach ($projects as $i => $project) {
          global $post;
          $post = $project;
          $id = get_post_meta($post->ID, 'project_id')[0];
          $html = '<tr>
          <td><a href="#update_project" onclick="window.update_project('.$id.','.$post->ID.')">'.get_the_title().'</a></td>
          <td>'.get_the_date().'</td>
          <td>'.get_post_meta($post->ID, 'validador_nombre')[0].'</td>
          <td><a href="#delete_project" onclick="window.delete_project('.$id.','.$post->ID.')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Borrar</a></td>
          </tr>';
          echo $html;
        }
      ?>
      
      </tbody>
      </table>
  <form name="update_project" action="<?php the_permalink(2); ?>" method="post">
    <input id="project_id" type="hidden" name="project_id" value="0">
    <input id="project_post_id" type="hidden" name="project_post_id" value="0">
  </form>
<!-- Desde acá el modal -->
<div class="modal fade" id="deleteWarning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
      </div>
      <div class="modal-body">
        <p>Al borrar este proyecto, Ud. lo elimina de la base de datos pública en donde estaba alojado. Una copia de este proyecto quedará registrada en una segunda base de datos, donde se habría ingresado el estado de avance del proyecto en su ejecución.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="document.forms['delete_project'].submit(); return false;"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Borrar</button>
      </div>
    </div>
  </div>
</div>
<!--hasta acá el modal-->
<form name="delete_project" action="." method="post">
  <input id="del_project_id" type="hidden" name="del_project_id" value="0">
  <input id="del_project_post_id" type="hidden" name="del_project_post_id" value="0">
</form>

<h3>Crear proyecto</h3>
<p>En una nueva página se desplegará un formulario en el que debe rellenar cada campo solicitado. Después de revisar los datos ingresados, Usted debe agregar algunos datos personales que lo harán responsable de la versión del proyecto que se ingresar a la base de datos.</p>
<p style="margin-bottom:50px;"><a class="btn btn-warning" href="<?php the_permalink(2); ?>" role="button">Crear nuevo proyecto</a></p>

      </div><!--col-->
</div><!--row-->
</section><!--container-->
<script type="text/javascript">
  var $ = jQuery;
  function update_project(id, pid){
    document.getElementById('project_id').value = id;
    document.getElementById('project_post_id').value = pid;
    document.forms['update_project'].submit();
    return false;
  }
  function delete_project(id, pid){
    $('#deleteWarning').modal('show');
    document.getElementById('del_project_id').value = id;
    document.getElementById('del_project_post_id').value = pid;
    return false;
  }
</script>
<?php get_footer();?>
