<!-- footer.php-->
	<?php $options = get_option('consultation_options'); ?>
	</div>
	
	<div class="footspace">
		<div class="container"> 
			<div class="col-md-12 text-center credits">
				Ο ιστοχώρος έχει σχεδιαστεί και υλοποιηθεί απο την <a href="http://opengov.pdm.gov.gr/pdm-ode/">ΟΔΕ Ανοικτής Διακυβέρνησης της ΠΔΜ</a> και την <a href="http://opengov.pdm.gov.gr/episey/">Ομάδα Ανοικτής Διακυβέρνησης του ΕΠΙΣΕΥ</a>.
			</div>
		</div>
	</div>
	
	</section>
	
	<footer>
		<div class="container">
			<div class="col-md-4">
				<?php echo NAME; ?><br />
				<a href="<?php echo URL; ?>" title="<?php echo DESCRIPTION; ?>"><?php echo DESCRIPTION; ?></a>
			</div>
			<div class="col-md-4 text-center">
				<?php if($options['footer_content'] == '') echo '&nbsp;'; else echo $options['footer_content']; ?>
			</div>
			<div class="col-md-4 text-right">
				Mε χρήση του <a href="http://mathe.ellak.gr" target="_blank" title="ΕΛ/ΛΑΚ">ΕΛ/ΛΑΚ</a> 
				<a href="http://wordpress.org" target="_blank" title="Wordpress.org">Wordpress</a>.<br />
				Άδεια <a href="http://creativecommons.org/licenses/by/3.0/gr/" target="_blank" title="Creative Commons Licence">Creative Commons</a>.
				<a href="http://creativecommons.org/licenses/by/3.0/gr/" target="_blank" title="Creative Commons Licence">
					<img src="<?php echo IMG; ?>/cc.png" title="Creative Commons Licence" alt="Creative Commons Licence" />
				</a>
			</div>
		</div>
	</footer>
	<div class="ribbon-wrapper"><a href="/beta/"><div class="ribbon">BETA</div></div>
	<?php wp_footer(); ?>
	
<?php 
	wp_footer(); 
	//show_wp_stats();
	echo $options['analytics_content'];
?>
</body>
</html>