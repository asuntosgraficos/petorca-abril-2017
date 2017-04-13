<?php session_start(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, user-scalable=0, minimum-scale=1, maximum-scale=1">
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
<meta name="title" content="<?php bloginfo('name'); ?>" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/style.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Khand:400,500" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Titillium+Web:600" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
    <link href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css" rel="stylesheet" data-semver="3.0.1" data-require="normalize@*" />
    <style>
      .legend {                                                   /* NEW */
        font-size: 12px;                                              /* NEW */
      }                                                           /* NEW */
      rect {                                                      /* NEW */
        stroke-width: 2;                                          /* NEW */
      }                                                           /* NEW */
    </style>
<?php wp_head();?>
</head>

<body>
<header class="container-fluid">
<div class="container">
<div class="row">
<div class="col-sm-2"><a href="http://www.municipalidadpetorca.cl" rel="nofollow" target="_blank"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/escudo-petorca.png" class="img-responsive"></a></div>
<div class="col-sm-8"><h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1></div>
<div class="col-sm-2">

<?php if (is_page()){?>
<a type="button" class="btn btn-danger" href="<?php echo wp_logout_url( home_url() ); ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> SALIR</a>
<?php }else{ ?>
<a type="button" class="btn btn-danger" href="https://www.sem.gob.cl/pago/index.php?&inst_id=69050500">PAGAR PATENTE</a>
<?php };?>

</div>

</div><!--row-->
</div><!--container-->
</header><!--container-fluid-->