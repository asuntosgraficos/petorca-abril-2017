<?php get_header(); ?>
<section class="container">
<?php global $wp_query;
$new_query = array_merge($wp_query->query_vars, array('post_type' => 'projects'));
query_posts($new_query);?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php $project = get_the_project();?>
<div class="row" id="primario">
<div class="col-sm-7">
<h3><?php the_title();?></h3>
<h4><?php echo $project->category; ?> en la Municipalidad de <?php echo $project->municipality; ?></h4>
</div>
<div class="col-sm-5">
<h2><?php echo '<span>$</span> '.number_format($project->budget_gen, 0, ',', '.'); ?></h2>
</div>
<div class="col-sm-7">
<?php $project_img =  get_project_media('projectFeaturedImage', 'medium', 'class=img-responsive foto');
if ($project_img) echo $project_img;
else echo '<img src="'.get_template_directory_uri().'/images/proyecto.jpg" class="img-responsive foto">';?>
<?php echo apply_filters('the_content', $project->abstract); ?>
</div>
<div class="col-sm-5">
<h4 class="responsabilidad">Responsables del proyecto:</h4>
<!--ini primer responsable-->
<div class="media">
<div class="media-left media-middle">
<?php $manager_img_funcionario = get_project_media('managerImage1', 'thumbnail', 'class=media-object');
if ($manager_img_funcionario) echo $manager_img_funcionario;
else echo '<img src="'.get_template_directory_uri().'/images/perfil.png" class="media-object">';?>
</div>
<div class="media-body">
<p class="media-heading"><?php echo $project->managers[0]->name; ?></p>
<p>Funcionario municipal<br/>
<a href="mailto:<?php echo $project->managers[0]->email; ?>"><?php echo $project->managers[0]->email; ?></a></p>
</div>
</div>
<!--fin primer responsable-->
<!--ini segundo responsable-->
<div class="media">
<div class="media-left media-middle">
<?php $manager_img_profesional = get_project_media('managerImage2', 'thumbnail', 'class=media-object');
if ($manager_img_profesional) echo $manager_img_profesional;
else echo '<img src="'.get_template_directory_uri().'/images/perfil.png" class="media-object">';?>
</div>
<div class="media-body">
<p class="media-heading"><?php echo $project->managers[1]->name; ?></p>
<p>Profesional del proyecto<br />
<?php echo $project->managers[1]->charge; ?> en <?php echo $project->managers[1]->business; ?></p>
</div>
</div>
<!--fin segundo responsable-->
<!--ini tercer responsable-->
<div class="media">
<div class="media-left media-middle">
<?php $manager_img_ejecutivo = get_project_media('managerImage3', 'thumbnail', 'class=media-object');
if ($manager_img_ejecutivo) echo $manager_img_ejecutivo;
else echo '<img src="'.get_template_directory_uri().'/images/perfil.png" class="media-object">';?>
</div>
<div class="media-body">
<p class="media-heading"><?php echo $project->managers[2]->name; ?></p>
<p>Ejecutor del proyecto<br/>
<?php echo $project->managers[2]->charge; ?> en <?php echo $project->managers[2]->business; ?></p>
</div>
</div>
<!--fin tercer responsable-->

</div><!--fin data basica-->
</div><!--/row #primario-->
<div class="row" id="secundario">
<div class="col-sm-7">
<div class="contenidos">
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Proyecto</a></li>
<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Presupuesto</a></li>
<li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Planificación</a></li>
</ul>
<div class="tab-content">
<!--primer panel-->
<div role="tabpanel" class="tab-pane active" id="home">
<p><strong>Ubicación</strong>: <?php echo $project->place; ?></p>
<hr />
<?php echo apply_filters('the_content', $project->background); ?>
<?php $project_img =  get_project_media('projectActualImage', 'medium', 'class=img-responsive foto');
if ($project_img) echo $project_img;
else echo '<img src="'.get_template_directory_uri().'/images/proyeto.jpg" class="responsiva">'; ?>
<?php echo apply_filters('the_content', $project->description); ?>
<?php $project_img =  get_project_media('projectResultImage', 'medium', 'class=img-responsive foto');
if ($project_img) echo $project_img;
else echo '<img src="'.get_template_directory_uri().'/images/proyeto.jpg" class="responsiva">';?>
</div>
<!--segundo panel-->
<div role="tabpanel" class="tab-pane" id="profile">
<p><?php the_title();?>: Inversión total de <?php echo '<span>$</span> '.number_format($project->budget_gen, 0, ',', '.'); ?>. <?php echo html_entity_decode ($project->budget_desc); ?></p>
<hr />
<h5>Detalles de valores parciales</h5>
<div class="row">
<div class="col-md-6">
<ol>
<?php foreach($project->budgets as $i => $budget){?>
<li>
<dl>
<dt>
<?php echo $budget->name; ?><br />
<?php echo '<span>$</span> '.number_format($budget->value, 0, ',', '.'); ?><br />
</dt>
<dd><?php echo $budget->justify; ?></dd>
</dl>
</li>
<?php };?>
</ol>
</div>
<div class="col-md-6">
<div id="chart"></div>
<script data-require="d3@*" data-semver="4.0.0" src="https://d3js.org/d3.v4.min.js"></script>
<script>
(function(d3) {
'use strict';
var dataset = [
<?php foreach($project->budgets as $i => $budget){?>
{ label: '<?php echo $budget->name; ?>', count: <?php echo (ceil(($budget->value*100)/$project->budget_gen));?>},
<?php };?>
];
var width = 250;
var height = 250;
var radius = Math.min(width, height) / 2;
var donutWidth = 25;
var legendRectSize = 15;
var legendSpacing = 5;
var color = d3.scaleOrdinal(d3.schemeCategory20b);
var svg = d3.select('#chart')
.append('svg')
.attr('width', width)
.attr('height', height)
.append('g')
.attr('transform', 'translate(' + (width / 2) +
',' + (height / 2) + ')');
var arc = d3.arc()
.innerRadius(radius - donutWidth)
.outerRadius(radius);
var pie = d3.pie()
.value(function(d) { return d.count; })
.sort(null);
var path = svg.selectAll('path')
.data(pie(dataset))
.enter()
.append('path')
.attr('d', arc)
.attr('fill', function(d, i) {
return color(d.data.label);
});
var legend = svg.selectAll('.legend')
.data(color.domain())
.enter()
.append('g')
.attr('class', 'legend')
.attr('transform', function(d, i) {
var height = legendRectSize + legendSpacing;
var offset =  height * color.domain().length / 2;
var horz = -4 * legendRectSize;
var vert = i * height - offset;
return 'translate(' + horz + ',' + vert + ')';
});
legend.append('rect')
.attr('width', legendRectSize)
.attr('height', legendRectSize)
.style('fill', color)
.style('stroke', color);
legend.append('text')
.attr('x', legendRectSize + legendSpacing)
.attr('y', legendRectSize - legendSpacing)
.text(function(d) { return d; });
})(window.d3);
</script>
</div>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="messages">
<p><?php the_title();?> comienza a ejecutarse el día <?php echo $project->hum_start_date; ?>, y será entregado por la Municipalidad de <?php echo $project->municipality; ?> el día <?php echo $project->hum_delivery_date; ?>.</p>
<hr />
<h5>Detalles de etapas y plazos</h5>
<ol>
<?php foreach($project->phases as $i => $phase){?>
<li>
<dl>
<dt><?php echo $phase->name; ?><br />
Desde el <?php echo $phase->hum_start_date; ?> hasta el <?php echo $phase->hum_end_date; ?>.</dt>
<dd><?php echo html_entity_decode ($phase->description); ?></dd>
</dl>
</li>
<?php };?>
</ol>
</div>
</div>
</div>
</div>
<div class="col-sm-5">
<!--nada-->
</div>
</div><!--/row #primario-->
<?php endwhile; ?>
<?php else : ?>
<?php endif; ?>
<?php wp_reset_query(); ?>
</section><!--/container-->
<?php include('footer.php');?>