<div class="col-md-4">
<div id="sidebar">

	<?php 
		$category = get_the_category();
		$options = get_option('consultation_options');	
		$cons_cat;
		foreach ($category as $cat) {
			if($cat->cat_ID == $options['cat_open'] or $cat->cat_ID ==$options['cat_close'] or $cat->cat_ID ==$options['cat_results']) continue;
			$cons_cat = $cat;  
		}			
	?>

	<div class="sidespot red_spot">
		
		<?php 
			$expires = explode('@', $cons_cat -> category_description );
			$countdate =  '"'.mysql2date("m/d/Y H:i", $expires[1]).'"'; 
		?>
		<div class="counter">
			Αναρτήθηκε<br />
			<span><?php echo mysql2date("j F Y, H:i", $expires[0]); ?></span><br />
			Ανοικτή σε Σχόλια έως<br />
			<span><?php echo mysql2date("j F Y, H:i", $expires[1]); ?></span>
		</div>
		
		<script language="JavaScript">
			TargetDate = <?php echo $countdate; ?>;
			BackColor = "#FFFFCC";
			ForeColor = "navy";
			CountActive = true;
			CountStepper = -1;
			LeadingZero = true;
			DisplayFormat = "Απομένουν %%D%% Ημέρες";
			FinishMessage = "Ολοκληρώθηκε.";
		</script>
		<script language="JavaScript" src="<?php echo JS; ?>/countdown.js"></script>
	</div>
	
	<?php if (comments_open()) {
		if ((!in_category($options['cat_open'])) && (!in_category($options['cat_close'])) && (!in_category($options['cat_results']))){	?>
		<div class="sidespot comment_spot">
			<a href="#respond" class="respond_below">Σχολιάστε!</a>
		</div>
	<?php } } ?>
	
	<?php
	if ( function_exists( 'get_downloads' ) ){
		$dl = get_downloads('category='.$cons_cat->cat_ID.'');
		if (!empty($dl)) { ?>
		<div class="sidespot orange_spot">
			<h4>Σχετικό Υλικό</h4>
			<?php	foreach($dl as $d) {
					$date = date("j F Y, H:s", strtotime($d->date));
					echo '<span class="file"><a href="'.$d->url.'" title="(Έκδοση '.$d->version.') Μεταφορτώθηκε '.$d->hits.' φορές" >'.$d->title.'</a></span>';
			 } ?>
		</div>
	<?php } } ?>

	<div class="sidespot">
		<?php if (comments_open()) { ?>
		<h4>Παρακολουθήστε</h4>
		<?php if(!(in_category($options['cat_open']) || in_category($options['cat_close']))){ ?>
		<span class="rss">
			<a href="<?php echo URL; ?>/?feed=rss2&p=<?php echo $post->ID; ?>">Σχόλια επι του Άρθρου</a>
		</span>
		<?php } ?>
		<span class="rss_gray">
			<a href="<?php echo URL; ?>/?feed=comments-rss2&cat=<?php echo $cons_cat->cat_ID; ?>">Σχόλια επι της Διαβούλευσης</a>
		</span>		
		<span class="rss_all">
			<a href="<?php echo URL; ?>/?feed=comments-rss2">Όλα τα Σχόλια</a>
		</span>	
	
		<span class="seperator"></span>
		<?php } ?>
		<h4>Εργαλεία</h4>
		<span class="print">
			<a href="<?php echo URL; ?>/?p=<?php echo $post->ID; ?>&print=1">Εκτύπωση</a>
		</span>
		<?php if(!is_open($cons_cat->cat_ID)){ ?>
		<span class="export">
			Εξαγωγή Σχολίων σε &nbsp;&nbsp;
			<a href="<?php echo URL; ?>/?ec=<?php echo $cons_cat->cat_ID; ?>&t=xls" title="XLS"><img src="<?php echo IMG; ?>/excel.gif" /></a>&nbsp;&nbsp;
			<a href="<?php echo URL; ?>/?ec=<?php echo $cons_cat->cat_ID; ?>&t=json" title="JSON"><img src="<?php echo IMG; ?>/json.png" width="16px" /></a>&nbsp;&nbsp;
			<a href="<?php echo URL; ?>/?ec=<?php echo $cons_cat->cat_ID; ?>&t=xml" title="XML"><img src="<?php echo IMG; ?>/xml.gif" /></a>
		</span>
		<?php } ?>
		<span class="seperator"></span>

		<h4>Στατιστικά</h4>
		<?php if(!(in_category($options['cat_open']) || in_category($options['cat_close']))){ ?>
		<span class="comments">
			<?php comments_popup_link('0 Σχόλια επι του Άρθρου','1 Σχόλιο επι του Άρθρου','% Σχόλια επι του Άρθρου'); ?>
		</span>
		<?php } ?>
		<span class="comments_cons">
		<?php
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
				AND t1.term_id = ".$cons_cat->cat_ID."";
				$cons_comments = $wpdb->get_var($sql);
		?>
			<?php echo $cons_comments ; ?> Σχόλια επι της <a href="<?php echo URL.'/?cat='.$cons_cat->cat_ID; ?>">Διαβούλευσης</a>
		</span>
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
		
		<?php /*
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
		</ul> */ ?>

		<span class="seperator"></span>
	
		<h4>Όλες οι Διαβουλεύσεις</h4>
		<ul class="allthecons">
			<?php get_consultations_list();	?>
		</ul>
	</div>

</div>
</div>