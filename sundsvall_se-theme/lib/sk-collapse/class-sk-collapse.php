<?php
class SK_Collapse {

	function __construct() {
		add_shortcode('collapse', array(&$this, 'collapse_shortcode'));

		add_action('init', array(&$this, 'tinymce_collapse_button_init'));
	}

	/**
	 * Add collapse-button to TinyMCE
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function tinymce_collapse_button_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array(&$this, 'register_tinymce_collapse_button'));
		add_filter('mce_external_plugins', array(&$this, 'add_tinymce_collapse_button_plugin'));
	}

	function register_tinymce_collapse_button($buttons) {
		$buttons[] = "sk_collapse";
		return $buttons;
	}

	function add_tinymce_collapse_button_plugin($plugin_array) {
		$plugin_array['sk_collapse'] = get_template_directory_uri().'/lib/sk-collapse/sk_collapse.js';
		return $plugin_array;
	}

	function collapse_shortcode($atts, $content = null) {

		if(!$content) return;

		global $sk_collapseIDs;

		if(!is_array($sk_collapseIDs)) $sk_collapseIDs = array();

		// Create "ID" from title (spaces to dashes and lowercase)
		$collapseID = strtolower(str_replace(' ', '-', $atts['title']));

		// Make sure we set a unique id of collapse.
		if(in_array($collapseID, $sk_collapseIDs)) {

			$i = 1;
			$newID = $collapseID;

			while(in_array($newID, $sk_collapseIDs)) {
				$i += 1;
				// Not unique, append "_num".
				$newID = $collapseID.'_'.$i;
			}

			$collapseID = $newID;
		}

		array_push($sk_collapseIDs, $collapseID);

		$collapsTitle = $atts['title'];

		if(isset($atts['tag'])) {
			$tag = $atts['tag'];
		}

		$link = '<a data-toggle="collapse" href="#'.$collapseID.'" aria-expanded="false" aria-controls="'.$collapseID.'">'.$collapsTitle.'</a>';

		if(isset($tag) && $tag == 'h3') {
			$title = sprintf('<h3>%s</h3>', $link);
		} else if(isset($tag) && $tag == 'h4') {
			$title = sprintf('<h4>%s</h4>', $link);
		} else {
			$title = sprintf('<h2>%s</h2>', $link);
		}

		$c  = '<div class="sk-collapse">';
		$c .= $title;
		$c .= '<div class="collapse" id="'.$collapseID.'">';
		$c .= $content;
		$c .= '</div>';
		$c .= '</div>';

		return $c;

	}


}
