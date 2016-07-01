<?php 
	get_header();
	$options = get_option('consultation_options');
?>
	<div id="main_content">
		<div class="col-md-8">
		<div class="post">
			<?php
			if (in_category($options['cat_open'])){
			// Είναι εισαγωγικό: 
			$category_in = get_the_category();
			$cat_id_in ;
			foreach ($category_in as $cat_in) {
				$cat_id_in = $cat_in->cat_ID;
				if (($cat_id_in !=$options['cat_close'] ) && ($cat_id_in != $options['cat_open']) && ($cat_id_in != $options['cat_results'])){	break; }
			}
			
			//Φέρε άν υπάρχει Τελικό Σχέδιο
			query_posts('cat='.$options['cat_results']);  
			if (have_posts()) {
				while (have_posts()) { 
					the_post(); 
					$category = get_the_category();
					foreach ($category as $cat) {
						if ($cat_id_in == $cat->cat_ID){ 
						?>	
						<div class="results">
							<h3><?php the_title(); ?></h3>
							<div class="post_content has_results"><?php the_content(''); ?></div>
						</div>
						<?php
							break;
						}
					}		
				}
			}
			wp_reset_query() ;
			
			//Φέρε άν υπάρχει της ολοκλήρωσης			
				query_posts('cat='.$options['cat_close']);  
				if (have_posts()) {
					while (have_posts()) { 
						the_post(); 
						$category = get_the_category();
						foreach ($category as $cat) {
							if ($cat_id_in == $cat->cat_ID){ 
							?>				
								<h3 class="complete"><?php the_title(); ?></h3>
								<div class="post_content is_complete"><?php the_content(''); ?></div>
							<?php
								break;
							}
						}		
					}
				}
				wp_reset_query() ;
			}			
			?>
			
			<?php
			if (in_category($options['cat_close'])){
				$category_in = get_the_category();
				$cat_id_in ;
				foreach ($category_in as $cat_in) {
					$cat_id_in = $cat_in->cat_ID;
					if (($cat_id_in !=$options['cat_close'] ) && ($cat_id_in != $options['cat_open']) && ($cat_id_in != $options['cat_results'])){	break; }
				}
				
				//Φέρε άν υπάρχει Τελικό Σχέδιο
				query_posts('cat='.$options['cat_results']);  
				if (have_posts()) {
					while (have_posts()) { 
						the_post(); 
						$category = get_the_category();
						foreach ($category as $cat) {
							if ($cat_id_in == $cat->cat_ID){ 
							?>	
							<div class="results">
								<h3><?php the_title(); ?></h3>
								<div class="post_content has_results"><?php the_content(''); ?></div>
							</div>
							<?php
								break;
							}
						}		
					}
				}
				wp_reset_query() ;
			}
			?>
			
			<?php while (have_posts()) : the_post(); ?>
			
				<?php if (in_category($options['cat_close'])){ ?>				
					<h3 class="complete"><?php the_title(); ?></h3>
					<div class="post_content is_complete""><?php the_content(''); ?></div>
				<?php } else { ?>
					<h3><?php the_title(); ?></h3>
					<div class="post_content"><?php the_content(''); ?></div>
				<?php }  ?>
			<?php endwhile; ?> 
			<?php
			if (in_category($options['cat_close'])){
			// Είναι ολοκλήρωσης: Φέρε άν υπάρχει της εισαγωγής
				$category_in = get_the_category();
				$cat_id_in ;
				foreach ($category_in as $cat_in) {
					$cat_id_in = $cat_in->cat_ID;
					if (($cat_id_in !=$options['cat_close'] ) && ($cat_id_in != $options['cat_open'])){	break; }
				}
				
				query_posts('cat='.$options['cat_open']);  
				if (have_posts()) {
					while (have_posts()) { 
						the_post(); 
						$category = get_the_category();
						foreach ($category as $cat) {
							if ($cat_id_in == $cat->cat_ID){ 
							?>				
								<h3><?php the_title(); ?></h3>
								<div class="post_content"><?php the_content(''); ?></div>
							<?php
								break;
							}
						}		
					}
				}
				wp_reset_query() ;
			}			
			?>
			
			<?php
			$print_comments =false;
			$has_comments = get_post_meta($post->ID, 'has_comments', true); 
			if (((!in_category($options['cat_open'])) && (!in_category($options['cat_close'])) && (!in_category($options['cat_results']))) || ($has_comments==true) )
				$print_comments =true;
			
			if($print_comments){
				ob_start();
				comment_form(
					array(
						'title_reply'       => 'Σχολιάστε',
						  'title_reply_to'    => 'Απαντήστε στο σχόλιο ',
						  'cancel_reply_link' => 'Ακύρωση Απάντησης',
						'fields' => array(
							'author' => '<div class="form-group">' . 
										'<label  for="author">' . __('Όνομα', 'bootstrap-basic') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
										'<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' class="form-control" />' . 
									
										'</div>',
							'email'  => '<div class="form-group">' . 
										'<label  for="email">' . __('Email', 'bootstrap-basic') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
										'<input id="email" name="email" ' . ($html5 ? 'type="email"' : 'type="text"') . ' value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' class="form-control" />' . 
										
										'</div>',
							'url'    => '<div class="form-group">' . 
										'<label for="url">' . __('Website', 'bootstrap-basic') . '</label> ' .
										'<input id="url" name="url" ' . ($html5 ? 'type="url"' : 'type="text"') . ' value="' . esc_attr($commenter['comment_author_url']) . '" size="30" class="form-control" />' . 
										 
										'</div>',
							'acceptterms' => '<div class="form-group">' . 
								'<input id="acceptterms" name="acceptterms" type="checkbox"  '.$aria_req .' class="required" />  ' .
								'<label  for="acceptterms">' . __('Αποδέχομαι τους <a href="'.get_permalink(2).'" >όρους συμμετοχής</a> στη διαβούλευση.', 'bootstrap-basic') .  ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
								'</div>',
						),
						'comment_field' => '<div class="form-group">' . 
										'<label  for="comment">' . _x('Σχόλιο', 'noun') . '</label> ' . 
										'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" class="form-control"></textarea>' . 
										
										'</div>',
					)
				); 
				
				/**
				 * WordPress comment form does not support action/filter form and input submit elements. Rewrite these code when there is support available.
				 * @todo Change form class modification to use WordPress hook action/filter when it's available.
				 * @todo Change input submit class modification to use WordPress hook action/filter when it's available.
				 */
				$comment_form = str_replace('class="comment-respond"', 'class="col-sm-12 panel comment-respond" style="margin-top:15px;"', ob_get_clean());
				//$comment_form = str_replace('class="comment-form"', 'class="comment-form form form-horizontal"', ob_get_clean());
				$comment_form = preg_replace('#(<input\b[^>]*\s)(type="submit")#i', '$1 type="submit" class="btn btn-primary"', $comment_form);
				echo $comment_form;	
				unset($comment_allowed_tags, $comment_form); 
				}
			?>
			
			<div id="consnav">
			<?php get_cons_posts_list($post->ID,'Πλοήγηση στη Διαβούλευση'); ?>
			</div>
		</div>	
		</div>
		<?php get_sidebar(); ?>
		
	</div>	
<?php 
	if($print_comments){ 
		comments_template(); } 
	?>
</div>
<?php get_footer(); ?>