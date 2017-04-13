<?php get_header(); ?>
<?php get_sidebar(); ?>

<section class="container">
<div class="row">
<?php
// CUSTOM WP QUERY
global $wp_query;
$new_query = array_merge($wp_query->query_vars, array(
  'post_type' => 'projects'
));

query_posts($new_query);
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php
// Información del proyecto en la base de datos (std object)
$project = get_the_project();
?>
<div class="col-sm-6 col-md-4 portfolio-item">
  <div class="portfolio-inside">
    <a href="<?php the_permalink();?>">	
      <h3><?php the_title();?></h3>
      <?php 
      $project_img =  get_project_media('projectFeaturedImage', 'medium', 'class=responsiva');
      if ($project_img) echo $project_img;
      else echo '<img src="'.get_template_directory_uri().'/images/proyecto.jpg" class="responsiva">';
      ?>
    </a>
    <div class="row encargado">
      <div class="col-xs-9 text-right"><p>
      <?php echo $project->managers[1]->name; ?><br />
      Profesional Responsable del Proyecto</p></div>
      <div class="col-xs-3 text-left">
        <?php
        $manager_img = get_project_media('managerImage2', 'thumbnail', 'class=circular');
        if ($manager_img) echo $manager_img;
        else {
          echo '<img src="'.get_template_directory_uri().'/images/perfil.png" class="circular">';
        }
        ?>
      </div>
    </div>
    <?php echo apply_filters('the_excerpt', $project->abstract); ?>
    <h4><?php echo '<span>$</span> '.number_format($project->budget_gen, 0, ',', '.'); ?></h4>
    <!--
    <code style="display: block; padding: 0;">
      <pre style="font-size: 10px; margin: 0; height: 200px;">
      <?php print_r(get_the_project()); ?>
      </pre>
    </code>
    -->
    </div>
</div>

<?php endwhile; ?>
<?php else : ?>
<?php endif; ?>
<?php wp_reset_query(); ?>

<!--termina el loop-->
</div><!--row-->
</section>

<?php get_footer(); ?>
