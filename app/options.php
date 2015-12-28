<?php
	
	function getOptions() {
		$options = get_option('consultation_options');
		if (!is_array($options)) {
			$options['cat_close'] = '';
			$options['cat_open'] = '';
			$options['cat_results'] = 0;
			$options['footer_content'] = '';
			$options['analytics_content'] = '';
			$options['logo'] = '';
			$options['terms'] = '';
			update_option('consultation_options', $options);
		}
		return $options;
	}

	function add() {
		if(isset($_POST['cons_opt_save'])) {
			$options = getOptions();

			$options['cat_close'] = $_POST['cat_close'] ;
			$options['cat_open'] = $_POST['cat_open'] ;
			$options['cat_results'] = $_POST['cat_results'] ;
			$options['logo'] = $_POST['logo'] ;
			$options['terms'] = $_POST['terms'] ;
			
			// footer
			$options['footer_content'] = stripslashes($_POST['footer_content']);
			
			// analytics
			$options['analytics_content'] = stripslashes($_POST['analytics_content']);

			update_option('consultation_options', $options);

		} else {
			getOptions();
		}

	}

	function display() {
		$options = getOptions();
?>

<form action="#" method="post" enctype="multipart/form-data" name="blocks_form" id="blocks_form">
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Ρυθμίσεις Διαβουλεύσεων</h2>
		
		<p class="submit"><input class="button-primary" type="submit" name="cons_opt_save" value="Αποθήκευση" /></p>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Ειδικές Κατηγορίες</th>
					<td>
						 <?php $catz = get_categories('hide_empty=0'); ?>
						
						<select name="cat_open"  style="width:250px;">
							<?php foreach ($catz as $cat){
								if(strlen($cat->name)<50) {
									if ($cat->term_id == $options['cat_open']) {
										  echo '<option value="'.$cat->term_id.'" selected="selected">'.$cat->name.'</option>';	  
									} else {
									  echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';	 
									}
								}
							} ?>
						</select> - Εισαγωγική <br />
						 
						<select name="cat_close"  style="width:250px;"> 
							<?php foreach ($catz as $cat){
								if(strlen($cat->name)<50) {
									if ($cat->term_id == $options['cat_close']) {
										  echo '<option value="'.$cat->term_id.'" selected="selected">'.$cat->name.'</option>';	  
									} else {
									  echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';	 
									}
								}
							} ?>
						</select> - Κλεισίματος<br />
						
						<select name="cat_results"  style="width:250px;">
							<option value="0" <?php if (get_option('cat_results') == 0) { echo 'selected="selected"'; }?>>--Καμία--</option>';	 	
							<?php foreach ($catz as $cat){
								if(strlen($cat->name)<50) {
									if ($cat->term_id == $options['cat_results']) {
										  echo '<option value="'.$cat->term_id.'" selected="selected">'.$cat->name.'</option>';	  
									} else {
									  echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';	 
									}
								}
							} ?>
						</select> - Αποτελέσματα
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Κεφαλίδα<br/><small style="font-weight:normal;">Λoγότυπο (80 pixels πλάτος)</small></th>
					<td>
						<label>
							<?php if($options['logo'] != '') echo '<img src="'.$options['logo'].'" width="80"/>&nbsp;&nbsp;'; ?>
							<input type="text" name="logo" id="logo" class="code" size="40" value="<?php echo($options['logo']); ?>">&nbsp;
							Ανεβάστε το λογότυπό σας στα <a href="<?php echo URL; ?>/wp-admin/media-new.php">Πολυμέσα</a> ή ορίστε URL.
						</label>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Όροι Χρήσης<br/><small style="font-weight:normal;">Εμφανίζεται στη φόρμα σχολιασμού.</small></th>
					<td>
						<select name="terms" size="1">
						<option value="-1">---</option>
						<?php 
							$args = array( 'post_type' => 'page', 'posts_per_page' => -1, 'suppress_filters'=> false,);
							$myposts = get_posts($args); 
							foreach( $myposts as $post ) { 
								setup_postdata($post); 
								echo '<option value="'.$post->ID.'" ';
								if($post->ID == $options['terms']) { echo 'selected'; }
								echo '>'.$post->post_title.'</option>';
							}
						?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Υποσέλιδο<br/><small style="font-weight:normal;">HTML<br/> Στο κέντρο του υποσέλιδου.</small></th>
					<td>
						<label>
							<textarea name="footer_content" cols="50" rows="10" id="footer_content" class="code" style="width:98%;font-size:12px;"><?php echo($options['footer_content']); ?></textarea>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Analytics</th>
					<td>
						<label>
							<textarea name="analytics_content" cols="50" rows="10" id="analytics_content" class="code" style="width:98%;font-size:12px;"><?php echo($options['analytics_content']); ?></textarea>
						</label>
					</td>
				</tr>
			</tbody>
		</table>		
		
		<p class="submit">
			<input class="button-primary" type="submit" name="cons_opt_save" value="Αποθήκευση" />
		</p>
	</div>

</form>

<?php
	}
	if(isset($_POST['cons_opt_save'])) {
		add();
	}
display();



?>
