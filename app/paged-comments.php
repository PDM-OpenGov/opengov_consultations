<?php 
function paged_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?>  id="comment-<?php comment_ID() ?>">

	 <div class="user">
		<div class="author">
		 <?php comment_date('j F Y, H:s') ?> |  
		 <?php 
			$auth_link = $comment->comment_author_url ;  
			if (url_exists($auth_link)){ ?>
				 <strong><a href="<?php echo $auth_link; ?>" target="_blank" rel="nofollow"><?php comment_author(); ?></a></strong>
			<?php } else { ?>
				 <strong><?php comment_author(); ?></strong>
			<?php }	 ?>
		</div>
		 <div class="meta-comment">
			<a href="<?php echo URL; ?>/?c=<?php comment_ID() ?>" title="" class="permalink">Μόνιμος Σύνδεσμος</a>
			<?php
				global $post;
				if ('open' == $post->comment_status) { ?> 
				<div class="rate"><?php if(function_exists(ckrating_display_karma)) { ckrating_display_karma(); } ?></div>
			<?php } else { ?>
				<div class="rate"><?php //echo display_votes($comment->comment_ID);  ?></div>
			<?php } ?> 
		 </div>
	 </div>
	 
	<?php comment_text() ?> 
	<?php if ($comment->comment_approved == '0') { ?>
		<p>Το Σχόλιο σας θα δημοσιευθεί μόλις ελεγχθεί απο τον διαχειριστή.</p>
	<?php } ?>  
	 
	</li>
<?php } ?>