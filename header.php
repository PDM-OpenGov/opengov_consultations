<?php  redirector(); ?>
<?php $options = get_option('consultation_options'); ?>
<!--[if lt IE 7]>   <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9 ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>		<html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="<?php echo IMG; ?>/favicon.ico" />
	<link rel="shortcut icon" href="<?php echo IMG; ?>/favicon.ico" />
	
	<script src="<?php echo JS; ?>/modernizr.js"></script>
	
	<title><?php headtitles(); ?></title>
	
	<?php
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!--[if lt IE 8]>
<p class="browsehappy">
	<?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to be able to experience this site.', 'opengov'); ?>
</p><![endif]-->
<no-script>
 <p id="no-js-warning">
	<?php _e(' It seems you have <strong>disabled</strong> javascript. Please <strong><a href="http://www.enable-javascript.com/">enable javascript</a></strong> for this site to function properly.', 'opengov'); ?>
  </p>
</no-script>

	<header>
		<div class="container">
			<nav class="navbar og-top-menu" role="navigation">
			 <form class="navbar-form pull-right" method="get" action="http://opengov.pdm.gov.gr/" role="search">
				<div class="form-group">
				  <input type="text" name="s" class="form-control og-search" style=" border-radius: 0px !important;" placeholder="Αναζήτηση.." value="" />
				</div>
			</form>
			
			<?php
				wp_nav_menu( array(
					'menu'       		=> 'top-menu',
					'theme_location' 	=> 'top-menu',
					'container'  		=> false,
					'menu_class'        => 'nav navbar-nav pull-right',
					'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
					'walker'            => new wp_bootstrap_navwalker()
					)
				);
			?>
			</nav>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-4 logo">
					<a href="<?php echo URL; ?>" title="<?php echo NAME; ?>">	
						<?php if($options['logo'] != '') { ?>
							<img src="<?php echo $options['logo']; ?>" alt="<?php echo NAME; ?>" title="<?php echo NAME; ?>" class="pull-left" />
						<?php } ?>
						<?php echo NAME; ?><br />
						<span><?php echo DESCRIPTION; ?></span>
					</a>
				</div>
				<div class="col-md-8 text-right">
					<nav class="navbar og-main-menu" role="navigation">
						<?php
							// TODO: Make it collapsable
							wp_nav_menu( array(
								'menu'       		=> 'main-menu',
								'theme_location' 	=> 'primary',
								'container'  		=> false,
								'menu_class'        => 'nav navbar-nav pull-right',
								'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
								'walker'            => new wp_bootstrap_navwalker()
								)
							);
						?>
					</nav>
				</div>
			</div>
		</div>
	</header>
	
	<section>

	<?php global $cnt; echo $cnt;?>
	<div id="content">
		<div class="container">
		 <div class="col-md-12">
		<?php if((!is_home()) || (!empty($_GET[c])) ){ my_breadcrumb(); } ?>
