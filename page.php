<?php 
	get_header();
	$options = get_option('consultation_options');
?>
<div id="main_content">
	<div class="post clearfix">
	
		<?php while ( have_posts() ) : the_post(); ?>
		
			<h3><?php the_title(); ?></h3>
			<div class="post_content"><?php the_content(''); ?></div>
			
		<?php endwhile; // end of the loop. ?>
		
	</div>	
	<?php get_sidebar(); ?>
</div>	
<?php get_footer(); ?>