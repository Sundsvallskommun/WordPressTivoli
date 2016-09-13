<?php
/**
 * Post and page content lead paragraph
 *
 * Automatically make first paragraph of post content the lead. Show this
 * visually in editor aswell.
 *
 * @since 1.0.0
 */

class SK_Page_Lead {

	function __construct() {
		add_filter('the_content', array(&$this, 'frontend_lead'));
		add_action('mce_external_plugins', array(&$this, 'sk_register_page_lead_tinymce_js'));

		add_action('admin_head-post.php', array(&$this, 'lead_save_validation'));
		add_action('admin_head-post-new.php', array(&$this, 'lead_save_validation'));
	}

	/**
	* Add .lead class to first paragraph.
	*
	* @author Johan Linder <johan@flatmate.se>
	*
	* @param string $content Content to add lead to
	*
	* @return string
	* */
	function frontend_lead($content){

		if(is_easyread()) {
			return $content;
		}

		return preg_replace('/<p([^>]+)?>/', '<p$1 class="lead">', $content, 1);
	}

	/**
	* Load tinyMCE plugin that adds .lead class to first paragraph while editing.
	* */
	function sk_register_page_lead_tinymce_js($plugin_array) {
		$plugin_array['sk_page_lead'] = get_template_directory_uri().'/lib/sk-page-lead/sk-tinymce-lead.js';
		return $plugin_array;
	}

	/**
	 * Validate content before save so make sure lead is under 160 characters.
	 */
	function lead_save_validation() {
		global $post;

		if( is_admin() && ($post->post_type == 'page' || $post->post_type == 'post') ) {
			$this->lead_validation_js();
		}
	}

	/**
	 * JS used to check that lead is under 160 characters long before allowing
	 * post submit.
	 */
	function lead_validation_js() {
		?>
			<script>
				jQuery(document).ready(function($) {
					$('#publish').on('click', function() {
						var leadtext = tinyMCE.get('content').dom.select('p:first')[0].innerHTML;
						if(leadtext.length > 160) {
							alert('Ingressen är för lång, var vänlig korta ner den. Ingressen får max vara 160 tecken och är nu ' + leadtext.length + ' tecken lång.')
							return false;
						}
					});
				});
			</script>
		<?php
	}

}
