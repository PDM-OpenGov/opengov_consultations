<?php

// Names and stuff
define('URL',get_bloginfo('url'));
define('NAME',get_bloginfo('name'));
define('DESCRIPTION',get_bloginfo('description'));
define('RSS',get_bloginfo('rss2_url'));

// Define folder constants
define('ROOT', get_bloginfo('template_url'));
define('JS', ROOT . '/js');
define('CSS', ROOT . '/css');
define('IMG', ROOT . '/images');

add_filter('the_category','the_category_filter',10,2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'parent_post_rel_link');
remove_action('wp_head', 'feed_links_extra');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'start_post_rel_link');
add_filter('the_generator', 'remove_generator');
remove_action('wp_head', 'wp_generator');

require_once(TEMPLATEPATH . '/app/func.php');
require_once(TEMPLATEPATH . '/app/paged-comments.php');
require_once(TEMPLATEPATH . '/app/menu.php');
require_once(TEMPLATEPATH . '/app/export_xls.php');

function remove_generator() {
	return '<generator>http://wordpress.org/</generator>';
}
// No Dashbar for logged in users (1of2)
remove_action( 'personal_options', '_admin_bar_preferences' ); 
// No Dashbar for logged in users (2of2)
add_filter('show_admin_bar', '__return_false'); 


add_action( 'wp_enqueue_scripts', 'opengov_scripts' );
function opengov_scripts(){
	
	if( !is_admin()){
		wp_enqueue_script("jquery");

		wp_enqueue_style( 'opensans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700&subset=latin,greek' );
		wp_enqueue_style( 'bootstrap', CSS.'/bootstrap.min.css' );
		wp_enqueue_style( 'opengov', ROOT.'/style.css' );
		
		
		wp_enqueue_script( 'bootstrap-js', JS . '/bootstrap.min.js', array( 'jquery' ) , '1.2.0' , true );
		wp_enqueue_script( 'viewport-ie', JS . '/ie10-viewport-bug-workaround.js', array( 'jquery' ) , '1.2.0' , true );
		
	}
}

require_once('app/wp_bootstrap_navwalker.php');
//require_once('lib/wp_bootstrap_comment.php');

?>