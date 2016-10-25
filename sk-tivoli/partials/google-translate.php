		<p class="dropdown-header m-b-0" lang="en">Translate</p>

		<p class="dropdown-item" lang="en" style="white-space: normal;"><small>Use Google to translate the web site. We take no responsibility for the accuracy of the translation.</small></p>

		<div class="dropdown-item" id="google_translate_element"></div>

<?php 
// Load translate script/styles in footer to prevent it from blocking rendering
add_action('wp_footer', function() {
?>
<script type="text/javascript">
	function googleTranslateElementInit() {
		new google.translate.TranslateElement({pageLanguage: 'sv', autoDisplay: false, multilanguagePage: false}, 'google_translate_element');
	}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php
});
?>
