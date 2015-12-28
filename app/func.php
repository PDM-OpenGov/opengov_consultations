<?php

function headtitles() {
	if ( is_home() ) { 
		echo NAME;
		echo (' | '.DESCRIPTION.'');}
	else{
		wp_title(''); 
		echo (' | ');
		echo NAME; }
}

function the_category_filter($thelist,$separator=' ') {  
	if(!defined('WP_ADMIN')) {  
        //Category IDs to exclude 
		$options = get_option('consultation_options');			
        $exclude = array($options['cat_close'],$options['cat_open'],$options['cat_results']);  
     
        $exclude2 = array();  
        foreach($exclude as $c) {  $exclude2[] = get_cat_name($c); }  
     
           $cats = explode($separator,$thelist);  
           $newlist = array();  
           foreach($cats as $cat) {  
              $catname = trim(strip_tags($cat));  
             if(!in_array($catname,$exclude2))  
                  $newlist[] = $cat;  
           }  
          return implode($separator,$newlist);  
       } else {  
           return $thelist;  
      }  
  } 

/* Return Links to a Given RSS Feed ************************/
function getRSSFeed($url, $numitems = '5', $before='<li>', $after='</li>')
{
	if(!is_null($url)){	
		require_once(ABSPATH. "wp-includes/rss-functions.php");
		$rss = fetch_rss($url);
		if($rss)
			foreach(array_slice($rss->items,0,$numitems) as $item)
				echo "$before<a title=\"".$item['description']."\" href=\"".$item['link']."\" target=\"_blank\">".$item['title']."</a>$after";
		else
			echo "<!--An error occured! There is a possibility that your feed may be badly formatted.<br />Error Message: " . magpie_error() ."-->";
	}
	else
		echo "<!--An error occured! No url was specified.-->";
}

//@link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
function post_is_in_descendant_category( $cats, $_post = null ){

	foreach ( (array) $cats as $cat ) {
		$descendants = get_term_children( (int) $cat, 'category');
		if ( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}

 // @link http://wordpress.org/support/topic/272978
function is_descendant_category($cat_id){

	if ( is_category() ) {
		$cat = get_query_var('cat');
		$args = array(
			'include' => $cat,
			'hide_empty' => 0
		  );
		$categories = get_categories($args);
		if ( ($cat == $cat_id) || ($categories[0]->category_parent == $cat_id) ) {
		return true;
	  }
	  return false;
	}
}

/* If We Really Are Home ************************/
function is_really_home(){

	$curent_url=$_SERVER['REQUEST_URI'];
	if (strlen($curent_url)==1 ){  
			return true;
		}
	return false;
}

/* Setup Menu ************************/
function menu_setup() {
	register_nav_menu('top-menu', __( 'Top Menu', 'opengov'));
	register_nav_menu('main-menu', __( 'Main Menu', 'opengov'));
	function fallbackfmenu(){ echo 'Ορίστε Πρώτα Μενού'; }	
}

add_action( 'after_setup_theme', 'menu_setup' );

/* Checks If a Url Exists ************************/
 function url_exists($url){
	if(preg_match('#http\:\/\/[aA-zZ0-9\.]+\.[aA-zZ\.]+#',$url)) return true;
    else return false;
} 

function my_breadcrumb() {
        
		echo '<div class="mybreadcrumb"><a class="home" href="';
		echo URL;
		echo '">';
		echo 'Αρχική';
		echo "</a> ";
		if ( is_category() ) {
			single_cat_title( ); 
		}
		if(is_single()) { 
			$category = get_the_category();
			$options = get_option('consultation_options');			
			foreach ($category as $cat) {
			$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open'])){
					echo '<a href="'.URL.'/?cat='.$ID.'">'.$cat -> cat_name.'</a>';
					//echo '<a href="'.get_permalink().'">'.$cat -> cat_name.'</a>';
					break;
				}
			}			
			echo '<span class="single">'; 
			the_title();
			echo '</span>'; }
		if(is_page()) {  the_title(); }
		if(is_tag()){ echo "Ετικέτα: ".single_tag_title('',FALSE); }
		if(is_search()){ echo "Αναζήτηση"; }
		if(is_year()){ echo get_the_time('Y'); }
		if (!empty($_GET[c])){
			$the_comment = get_comment($_GET[c]); 
			$category = get_the_category($the_comment->comment_post_ID);
			$options = get_option('consultation_options');			
			foreach ($category as $cat) {
			$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open'])){
					echo '<a href="'.URL.'/?cat='.$ID.'">'.$cat -> cat_name.'</a>';
					break;
				}
			}	
			echo '<span class="single">'; 
			echo '<a href="'.get_permalink($the_comment->comment_post_ID).'">'.get_the_title($the_comment->comment_post_ID).'</a>';	
			echo '</span>'; 			
			echo '<span class="single">'; 
			echo 'Σχόλιο του χρήστη ';
			echo $the_comment->comment_author ; 
			echo ' | ';
			echo mysql2date("j F Y, H:s", $the_comment->comment_date);
			echo '</span>'; }
		echo "</div>";
}

function show_wp_stats()
{
	global $user_ID; 
	if( $user_ID ) {
		if( current_user_can('level_10') ) {
			echo '<div class="wp_stats">';
			echo get_num_queries();
			echo ' Queries @ ';
			timer_stop(1);
			echo '</div>'; 
		}
	} 
}

function getCurrentCatID(){
	global $wp_query;
	$cat_ID = get_query_var('cat');
	return $cat_ID;
}

function get_consultations_list(){
	
	$closed_cats = array();
	$open_cats = array();
	$result_cats = array();
	$cat_namez = array();
	$cat_posts = array();
	$options = get_option('consultation_options');		
	
	// get closed ones
	query_posts('cat='.$options['cat_close']);  
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			$category = get_the_category();
			foreach ($category as $cat) {
				$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open']) && ($ID != $options['cat_results'])){
					$closed_cats[] =  $ID ;
					//$cat_namez[$ID] = $cat->cat_name;
					break;
				}
			}		
		}
	}
	
	// get closed ones
	query_posts('cat='.$options['cat_results']);  
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			$category = get_the_category();
			foreach ($category as $cat) {
				$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open']) && ($ID != $options['cat_results'])){
					$result_cats[] =  $ID ;
					//$cat_namez[$ID] = $cat->cat_name;
					break;
				}
			}		
		}
	}

	// get open ones
	query_posts('cat='.$options['cat_open']);  
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			global $post;
			$category = get_the_category();
			foreach ($category as $cat) {
				$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open']) && ($ID != $options['cat_results'])){
					$open_cats[] =  $ID ;
					$cat_namez[$ID] = $cat->cat_name;
					$cat_posts[$ID] = $post;
					break;
				}
			}		
		}
	}
	wp_reset_query() ;
	$open_cats = array_diff($open_cats, $closed_cats);
	$open_cats = array_values($open_cats);
	
	$closed_cats = array_diff($closed_cats, $result_cats);
	$closed_cats = array_values($closed_cats);
	
	$the_post = new stdClass();
	foreach ($open_cats as $cat) {
		$the_post = $cat_posts[$cat];
		echo '<li class="open"><a href="'.URL.'/?p='.$the_post->ID.'" title="'.$cat_namez[$cat].'">'.$cat_namez[$cat].'</a></li>';
	}
	
	foreach ($closed_cats as $cat) {
		$the_post = $cat_posts[$cat];
		echo '<li class="closed"><a href="'.URL.'/?p='.$the_post->ID.'" title="'.$cat_namez[$cat].'">'.$cat_namez[$cat].'</a></li>';
	}
	
	foreach ($result_cats as $cat) {
		$the_post = $cat_posts[$cat];
		echo '<li class="done"><a href="'.URL.'/?p='.$the_post->ID.'" title="'.$cat_namez[$cat].'">'.$cat_namez[$cat].'</a></li>';
	}
}

function get_consultations_list_index(){
	
	$closed_cats = array();
	$open_cats = array();
	$result_cats = array();
	$cat_namez = array();
	$cat_descr = array();
	$cat_posts = array();
	$options = get_option('consultation_options');		
	
	// get finished ones
	query_posts('cat='.$options['cat_results']);  
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			$category = get_the_category();
			foreach ($category as $cat) {
				$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open'])  && ($ID != $options['cat_results'])){
					$result_cats[] =  $ID ;
					//$cat_namez[$ID] = $cat->cat_name;
					break;
				}
			}		
		}
	}
	
	// get closed ones
	query_posts('cat='.$options['cat_close']);  
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			$category = get_the_category();
			foreach ($category as $cat) {
				$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open'])  && ($ID != $options['cat_results'])){
					$closed_cats[] =  $ID ;
					//$cat_namez[$ID] = $cat->cat_name;
					break;
				}
			}		
		}
	}

	// get open ones
	query_posts('cat='.$options['cat_open']);  
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			global $post;
			$category = get_the_category();
			foreach ($category as $cat) {
				$ID = $cat->cat_ID;
				if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open'])  && ($ID != $options['cat_results'])){
					$open_cats[] =  $ID ;
					$cat_descr[$ID] = $cat->category_description ;
					$cat_namez[$ID] = $cat->cat_name;
					$cat_posts[$ID] = $post;
					break;
				}
			}		
		}
	}
	wp_reset_query() ;
	// Keep only Open
	$open_cats = array_diff($open_cats, $closed_cats);
	$open_cats = array_values($open_cats);
	
	// Keep only Closed
	$closed_cats = array_diff($closed_cats, $result_cats);
	$closed_cats = array_values($closed_cats);
	
	$the_post = new stdClass();
	if(($_GET[type]!='closed') && ($_GET[type]!='done')){
		foreach ($open_cats as $cat) {
			$the_post = $cat_posts[$cat];
			echo '<div class="index_list_item open">';
			echo '<a class="short_title" href="'.URL.'/?p='.$the_post->ID.'" title="'.$cat_namez[$cat].'">'.$cat_namez[$cat].'</a>';
			echo '<div class="index_list_item_object">';
			echo '<a class="long_title" href="'.URL.'/?p='.$the_post->ID.'" title="'.$the_post->post_title.'">'.$the_post->post_title.'</a>';
			echo '<div class="index_list_item_content">';
			
			if ( empty($the_post->post_excerpt) )
				$excerpt = apply_filters('the_content', $the_post->post_content);
			else
				$excerpt = apply_filters('the_excerpt', $the_post->post_excerpt);
			$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			$excerpt = wp_html_excerpt($excerpt, 1200) . ' [...]';
			$excerpt = '<p>'.$excerpt.'</p><a class="more" href="'.URL.'/?p='.$the_post->ID.'">Περισσότερα &raquo;</a>';
			echo $excerpt;
			echo '</div>'; //index_list_item_content
			echo '</div>'; //index_list_item_object
			echo '<div class="index_list_item_details">';
			
			echo '<span class="expiredate">';
			$expires = explode('@', $cat_descr[$cat] );
			echo ''.mysql2date("j F Y, H:i", $expires[1]);
			echo '</span>';
			
			echo '<span class="comments">';
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->posts
				INNER JOIN $wpdb->term_relationships as r1 ON ($wpdb->posts.ID = r1.object_id)
				INNER JOIN $wpdb->term_taxonomy as t1 ON (r1.term_taxonomy_id = t1.term_taxonomy_id)
			WHERE 
				post_password = ''
				AND post_status =  'publish'
				AND t1.taxonomy = 'category'
				AND t1.term_id = ".$cat."";
				$posts = $wpdb->get_var($sql);
				$posts = $posts - 1;
			echo $posts.' <a href="'.URL.'/?p='.$the_post->ID.'#consnav">Σχολιάσιμα Άρθρα</a>';
			echo '</span>';
			
			echo '<span class="comments_cons">';
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->comments
				LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
				INNER JOIN $wpdb->term_relationships as r1 ON ($wpdb->posts.ID = r1.object_id)
				INNER JOIN $wpdb->term_taxonomy as t1 ON (r1.term_taxonomy_id = t1.term_taxonomy_id)
			WHERE comment_approved = '1'
				AND comment_type = ''
				AND post_password = ''
				AND t1.taxonomy = 'category'
				AND t1.term_id = ".$cat."";
				$cons_comments = $wpdb->get_var($sql);

			echo $cons_comments.' Σχόλια';
			echo '</span>';
			
			echo '<span class="rss_gray">';
			echo '<a href="'.URL.'/?feed=comments-rss2&cat='.$cat.'">Παρακολούθηση μέσω RSS</a>';
			echo '</span>';	
			if ($the_post->comment_status =='open'){
				echo '<span class="participate red_spot">';
				echo '<a href="'.URL.'/?p='.$the_post->ID.'#consnav">Συμμετοχή στη Διαβούλευση!</a>';
				echo '</span>';
			}
			echo '</div>';
			echo '</div>'; //index_list_item
		}
	}
	if(($_GET[type]!='open') && ($_GET[type]!='done')){
		foreach ($closed_cats as $cat) {
			$the_post = $cat_posts[$cat];
			echo '<div class="index_list_item closed">';
			echo '<a class="short_title" href="'.URL.'/?p='.$the_post->ID.'" title="'.$cat_namez[$cat].'">'.$cat_namez[$cat].'</a>';
			echo '<div class="index_list_item_object">';
			echo '<a class="long_title" href="'.URL.'/?p='.$the_post->ID.'" title="'.$the_post->post_title.'">'.$the_post->post_title.'</a>';
			echo '<div class="index_list_item_content">';
			
			if ( empty($the_post->post_excerpt) )
				$excerpt = apply_filters('the_content', $the_post->post_content);
			else
				$excerpt = apply_filters('the_excerpt', $the_post->post_excerpt);
			$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			$excerpt = wp_html_excerpt($excerpt, 1200) . ' [...]';
			$excerpt = '<p>'.$excerpt.'</p><a class="more" href="'.URL.'/?p='.$the_post->ID.'">Περισσότερα &raquo;</a>';
			echo $excerpt;
			echo '</div>'; //index_list_item_content
			echo '</div>'; //index_list_item_object
			echo '<div class="index_list_item_details">';
			
			echo '<span class="expiredate">';
			$expires = explode('@', $cat_descr[$cat] );
			echo ''.mysql2date("j F Y, H:i", $expires[1]);
			echo '</span>';
			
			echo '<span class="comments">';
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->posts
				INNER JOIN $wpdb->term_relationships as r1 ON ($wpdb->posts.ID = r1.object_id)
				INNER JOIN $wpdb->term_taxonomy as t1 ON (r1.term_taxonomy_id = t1.term_taxonomy_id)
			WHERE 
				post_password = ''
				AND post_status =  'publish'
				AND t1.taxonomy = 'category'
				AND t1.term_id = ".$cat."";
				$posts = $wpdb->get_var($sql);
				$posts = $posts - 2;
			echo $posts.' <a href="'.URL.'/?p='.$the_post->ID.'#consnav">Άρθρα</a>';
			echo '</span>';
					
			echo '<span class="comments_cons">';
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->comments
				LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
				INNER JOIN $wpdb->term_relationships as r1 ON ($wpdb->posts.ID = r1.object_id)
				INNER JOIN $wpdb->term_taxonomy as t1 ON (r1.term_taxonomy_id = t1.term_taxonomy_id)
			WHERE comment_approved = '1'
				AND comment_type = ''
				AND post_password = ''
				AND t1.taxonomy = 'category'
				AND t1.term_id = ".$cat."";
				$cons_comments = $wpdb->get_var($sql);

			echo $cons_comments.' Σχόλια';
			echo '</span>';
			echo '<span class="export">';
			echo 'Εξαγωγή Σχολίων σε ';
			echo '<a href="'.URL.'/?ec='.$cat.'&t=xls"><img src="'.IMG.'/excel.gif" /></a>';
			echo '</span>';
			echo '</div>';
			echo '</div>'; //index_list_item
		}
	}
	if(($_GET[type]!='open') && ($_GET[type]!='closed')){
		foreach ($result_cats as $cat) {
			$the_post = $cat_posts[$cat];
			echo '<div class="index_list_item done">';
			echo '<a class="short_title" href="'.URL.'/?p='.$the_post->ID.'" title="'.$cat_namez[$cat].'">'.$cat_namez[$cat].'</a>';
			echo '<div class="index_list_item_object">';
			echo '<a class="long_title" href="'.URL.'/?p='.$the_post->ID.'" title="'.$the_post->post_title.'">'.$the_post->post_title.'</a>';
			echo '<div class="index_list_item_content">';
			
			if ( empty($the_post->post_excerpt) )
				$excerpt = apply_filters('the_content', $the_post->post_content);
			else
				$excerpt = apply_filters('the_excerpt', $the_post->post_excerpt);
			$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			$excerpt = wp_html_excerpt($excerpt, 1200) . ' [...]';
			$excerpt = '<p>'.$excerpt.'</p><a class="more" href="'.URL.'/?p='.$the_post->ID.'">Περισσότερα &raquo;</a>';
			echo $excerpt;
			echo '</div>'; //index_list_item_content
			echo '</div>'; //index_list_item_object
			echo '<div class="index_list_item_details">';
			
			echo '<span class="expiredate">';
			$expires = explode('@', $cat_descr[$cat] );
			echo ''.mysql2date("j F Y, H:i", $expires[1]);
			echo '</span>';
			
			echo '<span class="comments">';
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->posts
				INNER JOIN $wpdb->term_relationships as r1 ON ($wpdb->posts.ID = r1.object_id)
				INNER JOIN $wpdb->term_taxonomy as t1 ON (r1.term_taxonomy_id = t1.term_taxonomy_id)
			WHERE 
				post_password = ''
				AND post_status =  'publish'
				AND t1.taxonomy = 'category'
				AND t1.term_id = ".$cat."";
				$posts = $wpdb->get_var($sql);
				$posts = $posts - 3;
			echo $posts.' <a href="'.URL.'/?p='.$the_post->ID.'#consnav">Άρθρα</a>';
			echo '</span>';
			
			echo '<span class="comments_cons">';
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->comments
				LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
				INNER JOIN $wpdb->term_relationships as r1 ON ($wpdb->posts.ID = r1.object_id)
				INNER JOIN $wpdb->term_taxonomy as t1 ON (r1.term_taxonomy_id = t1.term_taxonomy_id)
			WHERE comment_approved = '1'
				AND comment_type = ''
				AND post_password = ''
				AND t1.taxonomy = 'category'
				AND t1.term_id = ".$cat."";
				$cons_comments = $wpdb->get_var($sql);

			echo $cons_comments.' Σχόλια';
			echo '</span>';
						echo '<span class="export">';
			echo 'Εξαγωγή Σχολίων σε ';
			echo '<a href="'.URL.'/?ec='.$cat.'&t=xls"><img src="'.IMG.'/excel.gif" /></a>';
			echo '</span>';
			echo '<span class="participate blue_spot">';
			echo '<a href="'.URL.'/?p='.$the_post->ID.'">Δείτε τα Αποτελέσματα!</a>';
			echo '</span>';
			
			echo '</div>';
			echo '</div>'; //index_list_item
		}
	}
}


function get_cons_posts_list($p_id,$nav_title){
	$options = get_option('consultation_options');	
	$p_category = get_the_category($p_id);
	$c_ID;
	foreach ($p_category as $p_cat) {
		$ID = $p_cat->cat_ID;
		if (($ID !=$options['cat_close'] ) && ($ID != $options['cat_open'])){
			$c_ID = $ID; 
			break;
		}
	}
	query_posts('posts_per_page=-1&cat='.$c_ID); 
	echo '<ul class="other_posts">';
	echo '<h4 class="other_posts_nav">'.$nav_title.'</h4>';
	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			$category = get_the_category();
			if (count($category)>1) { continue; }
			else{
				global $post;
				if ($post->ID == $p_id){
					echo '<li class="other_posts_nav_current"><span class="list_comments">';
					comments_popup_link('0 Σχόλια','1 Σχόλιο','% Σχόλια'); 
					echo '</span><span class="list_current_title">';
					the_title(); 
					echo '</span></li>';
				}else{
					echo '<li><span class="list_comments">';
					comments_popup_link('0 Σχόλια','1 Σχόλιο','% Σχόλια'); 
					echo '</span><a class="list_comments_link" href="';
					the_permalink(); 
					echo '" title="';
					the_title(); 
					echo '">';
					the_title(); 
					echo '</a></li>';
				}
			}
		}
	}
	echo '</ul>';
	wp_reset_query() ;
}

function redirector(){
	// Redirect 404 Errors
	if (is_404() || is_search() || is_archive()){
		header("Location: ".URL."");
	}
	$options = get_option('consultation_options');	
	
	// Redirect Intro & Outro Categories
	if (is_category($options['cat_close']) || is_category($options['cat_open'])|| is_category($options['cat_results'])) {
		header("Location: ".URL."");
	}
	
	// Redirect Categories to Intro Posts
	if (is_category()){
		$cur_cat_id = getCurrentCatID(); 
		//global $cnt;
		query_posts('cat='.$options['cat_open']);  
		if (have_posts()) {
			while (have_posts()) { 
				the_post(); 
				global $post;
				if (in_category($cur_cat_id)) {
					header("Location: ".URL."/?p=".$post->ID);
				}	
			}
		}
		wp_reset_query() ;
	}
	// Redirect Old Comment Permalinks
	if ($_GET['option']== 'comment_view'){
		header("Location: ".URL."/?c=".$_GET[comment_id]);	
	}
	if (!empty($_GET['ec'])){
		include(TEMPLATEPATH."/export_data.php");
	}
}

function is_open($cons_id){
	global $wpdb;
    $poststable = $wpdb->posts;
	$tp = $wpdb->prefix;
	
	$sql= "SELECT COUNT(*) FROM $poststable  
			INNER JOIN {$tp}term_relationships as r1 
				ON ($poststable.ID = r1.object_id) 
			INNER JOIN {$tp}term_taxonomy as t1 
				ON (r1.term_taxonomy_id = t1.term_taxonomy_id) 
			WHERE  post_password = '' 
				AND comment_status = 'open' 
				AND t1.taxonomy = 'category' 
				AND t1.term_id = ".$cons_id."";
	
	$open_posts = $wpdb->get_var($sql);
	if ($open_posts > 0){ return true;	}
	return false;
}

?>