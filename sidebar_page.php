<div id="sidebar">

	<div class="sidespot">

		<h4>Στατιστικά</h4>
		<span class="comments_all">
		<?php
			global $wpdb;
			$sql =
			"SELECT count(*)
			FROM $wpdb->comments
			WHERE comment_approved = '1'
				AND comment_type = ''";
				$all_comments = $wpdb->get_var($sql);
		?>
			<?php echo $all_comments ; ?> - Όλα τα Σχόλια
		</span>
		
		<span class="seperator"></span>
		
		<ul class="share_them">
			<li>
				<!-- Tweet This -->
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="opengov_gr" data-related="#opengovgr">Tweet</a>
				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			</li>
			<li>	
				<!-- Facebook Share -->
				<a name="fb_share" type="box_count" href="http://www.facebook.com/sharer.php">Share</a>
				<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
			</li>
			<li>	
				<!-- Google Plus -->
				<div class="g-plusone" data-size="tall"></div>
				<script type="text/javascript">
				  (function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
			</li>
		</ul>

		<span class="seperator"></span>
	
		<h4>Όλες οι Διαβουλεύσεις</h4>
		<ul>
			<?php get_consultations_list();	?>
		</ul>
	</div>

</div>