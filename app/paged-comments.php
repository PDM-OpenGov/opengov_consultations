<?php 
/*
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
				<div class="rate"><?php 
					echo do_shortcode( '[rating-system-comments]');
					//if (class_exists('ZakiLikeDislike')) ZakiLikeDislike::getLikeDislikeHtml(); 
				?></div>
			<?php } else { ?>
				<div class="rate"><?php //echo display_votes($comment->comment_ID);  ?></div>
			<?php } ?> 
		 </div>
		 <?php comment_reply_link( array ( 'reply_text' => 'Απάντηση' ), comment_ID(), the_ID() ); ?>
	 </div>
	 
	<?php comment_text() ?> 
	<?php if ($comment->comment_approved == '0') { ?>
		<p>Το Σχόλιο σας θα δημοσιευθεί μόλις ελεγχθεί απο τον διαχειριστή.</p>
	<?php } ?>  
	 
	</li>
<?php } ?>
<?php */

function paged_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
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
				<div class="rate"><?php echo do_shortcode( '[rating-system-comments]'); ?></div>
			<?php } else { ?>
				<div class="rate"><?php //echo display_votes($comment->comment_ID);  ?></div>
			<?php } ?> 
		 </div>
		 <?php //comment_reply_link( array ( 'reply_text' => 'Απάντηση' ), comment_ID(), the_ID() ); ?>
	</div>
	<?php comment_text() ?> 
	<?php if ($comment->comment_approved == '0') { ?>
		<p>Το Σχόλιο σας θα δημοσιευθεί μόλις ελεγχθεί απο τον διαχειριστή.</p>
	<?php } ?>  
	<div class="reply">
	<?php  
			$comment_reply_link = get_comment_reply_link(array_merge($args, array(
			'add_below' => 'div-comment',
			'depth'     => $depth,
			'max_depth' => $args['max_depth'],
			'reply_text' => '<span class="fa fa-reply"></span> ' . __('Απαντηστε στο σχόλιο', 'bootstrap-basic'),
			'login_text' => '<span class="fa fa-reply"></span> ' . __('Σύνδεση για απάντηση', 'bootstrap-basic')
		)));
			echo str_replace('comment-reply-link','comment-reply-link btn btn-default',   $comment_reply_link); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

?>