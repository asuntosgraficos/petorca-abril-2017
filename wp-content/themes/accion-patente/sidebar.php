<aside class="container-fluid">

<?php query_posts('p=120');?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
$total = get_the_title();
$parcial = get_the_content();
$porcentaje = ceil(($parcial*100)/$total);
?>

<div class="container">
<!--contador-->
<div class="row">

<div class="col-sm-12">
<h2><?php echo '<span>$</span> '.number_format($parcial, 0, ',', '.'); ?><sup>*</sup></h2>
<h6>* Recaudación actualizada a las <?php the_modified_time('h:i \h\r\s\. \d\e\l j \d\e F\, Y'); ?></h6>
</div><!--col-sm-12-->
<div class="col-sm-1 col-xs-1 text-right">0</div>
<div class="col-sm-9 col-xs-7">
<div class="progress">
<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $porcentaje;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $porcentaje;?>%">
<?php echo $porcentaje;?> %
</div><!--progress-bar-->
</div><!--progress-->
</div><!--col-->
<div class="col-sm-2 col-xs-4 text-left"><?php echo '<span>$ </span> '.number_format($total, 0, ',', '.'); ?></div>

</div><!--row-->
</div><!--container-->

<?php endwhile; ?>
<?php else : ?>
<?php endif; ?>
<?php wp_reset_query(); ?>

</aside><!--container-fluid-->
