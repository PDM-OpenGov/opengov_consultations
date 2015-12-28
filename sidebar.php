<?php 
if (is_home()){
	
} elseif (is_single()){  
	include(TEMPLATEPATH."/sidebar_single.php");
}elseif (!empty($_GET[preview])){
	include(TEMPLATEPATH."/sidebar_preview.php");
} elseif (is_page()){ 
	include(TEMPLATEPATH."/sidebar_page.php");
}  ?>
