<div id="comments">
	
	<?php 
		if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) { die ('Σφάλμα!'); }

		if (!empty($post->post_password)) { 
			if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie?>
				<p class="nocomments">Απαιτείται Κωδικός.<p>
			<?php
				return;
			}
		}
	?>

	<?php if (have_comments()) { ?>
		<div class="comment_nav">
			<div class="nav">
				Σχόλια <?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ); ?>
				<?php global $comments; ?>
			</div>
		</div>
		
		<ul class="comment_list">
			<?php wp_list_comments('type=comment&reverse_top_level=1&callback=paged_comments'); ?>
		</ul>
		
		<div class="comment_nav">
			<div class="nav">
				Σχόλια <?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ); ?>
			</div>
		</div>
		
	<?php }  ?>
	
	<?php if ('open' == $post->comment_status) { ?> 
	<div id="respond" class="form_land">	
		<div class="comment_form">
			<h3 class="comment_on">Σχολιάστε</h3>
			<form class="form" action="<?php echo URL; ?>/wp-comments-post.php" method="post" id="commentform">
			
				<?php if ( $user_ID ) { ?>
					<p>Συνδεδεμένος ως <a href="<?php echo URL; ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. 
						<a href="<?php echo URL; ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Αποσύνδεση &raquo;</a></p>
				<?php } else { ?>

					<p><label for="author">
						<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" class="TextField" style="width: 210px;" />
						Όνομα (Υποχρεωτικό)
					</label></p>
							
					<p><label for="email">
						<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" class="TextField" style="width: 210px;" />
						eMail (Υποχρεωτικό) (Δεν Δημοσιεύεται)
					</label></p>
						
					<p><label for="url">
					<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" class="TextField" style="width: 210px;" />
					Προσωπικός Ιστοχώρος/Ιστοσελίδα/Blog
					</label></p>

				<?php }?>	
				<p>
					<textarea name="comment" id="comment" rows="20" tabindex="4" class="TextArea" ></textarea>
				</p>

				<p>
					<input name="SubmitComment" type="submit" class="SubmitComment"  title="Post Your Comment" value="Υποβολή του Σχολίου" alt="Υποβολή του Σχολίου" />
					<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				</p>
				<?php do_action('comment_form', $post->ID); ?>
			</form>
		</div>
		<div class="comments_guide">
			<?php 
				$options = get_option('consultation_options');
				$terms = get_post($options['terms']); ?>
			<h4><?php echo $terms->post_title; ?></h4>
			<?php echo $terms->post_content; ?>
		</div>
	</div>
	<?php } ?>
		
</div>