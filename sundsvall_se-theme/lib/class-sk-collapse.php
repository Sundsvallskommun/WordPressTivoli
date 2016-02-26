<?php
class SK_Collapse {

	function __construct() {
		add_shortcode('collapse', array(&$this, 'collapse_shortcode'));
	}

	function collapse_shortcode($atts, $content = null) {

		if(!$content) return;

		$collapsName  = 'foobarbiz';
		$collapsTitle = $atts['title'];
		$tag = $atts['tag'];

		$link = '<a data-toggle="collapse" href="#'.$collapsName.'" aria-expanded="false" aria-controls="'.$collapsName.'">'.$collapsTitle.'</a>';
		$title = sprintf('<%1$s>%2$s</%1$s>', $tag, $link);

		$c  = '<div class="">';
		$c .= $title;
		$c .= '<div class="collapse" id="'.$collapsName.'">';
		$c .= $content;
		$c .= '</div>';
		$c .= '</div>';

		return $c;

	}


}
