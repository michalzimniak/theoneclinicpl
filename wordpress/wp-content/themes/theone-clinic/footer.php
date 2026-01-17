<?php
/**
 * Footer
 */
?>

<footer class="main-footer">
	<div class="container">
		<div class="footer-content">
			<div class="footer-text">
				<p>&copy; <?php echo esc_html((string) wp_date('Y')); ?> TheOne Clinic. Wszelkie prawa zastrzeżone.</p>
				<p>Profesjonalne zabiegi medycyny estetycznej</p>
			</div>
			<div class="footer-social">
				<a href="<?php echo esc_url(theone_clinic_get_setting('theone_instagram_url')); ?>" target="_blank" class="social-link" aria-label="Instagram" rel="noopener">
					<i class="fab fa-instagram"></i>
				</a>
				<a href="<?php echo esc_url(theone_clinic_get_setting('theone_facebook_url')); ?>" target="_blank" class="social-link" aria-label="Facebook" rel="noopener">
					<i class="fab fa-facebook-f"></i>
				</a>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
