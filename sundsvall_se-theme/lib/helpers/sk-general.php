<?php

/**
 * Generate svg markup to load svg icon by id
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function the_icon($id, array $args = array()) {
	echo get_icon($id, $args);
}

function get_icon($id, array $args = array()) {

	$args = array_merge(array(
		'alt' => '',
		'width' => null,
		'height' => null
	), $args);

	$svg =  '<svg ';

	if($args['width']) { $svg .= 'width="'.$args['width'].'"'; }

	if($args['height']) { $svg .= 'height="'.$args['height'].'" '; }

	if($args['height'] && $args['width']) { $svg .= 'viewBox="0 0 '. $args['width'] . ' ' . $args['height'] .'"'; }

	$svg .= 'class="icon icon-'.$id.'"';
	$svg .=  '>';

	$svg .= '<title>'.$args['alt'].'</title>';
	$svg .= '<use xlink:href="#'.$id.'" />';

	$svg .= '</svg>';

	return $svg;
}

/**
 * Load svg sprites to head
 * */
add_action('wp_head', 'loadSvgSprites');

function loadSvgSprites() {
?>
  <!-- load combined svg file (with symbols) into body-->
  <script>
    (function (doc) {
      var scripts = doc.getElementsByTagName('script')
      var script = scripts[scripts.length - 1]
      var xhr = new XMLHttpRequest()
      xhr.onload = function () {
        var div = doc.createElement('div')
        div.innerHTML = this.responseText
        div.style.display = 'none'
        script.parentNode.insertBefore(div, script)
      }
      xhr.open('get', '<?php bloginfo('template_directory'); ?>/assets/images/icons.svg', true)
      xhr.send()
    })(document)
  </script>

<?php
}

/**
 * Format phone number with dash and spaces.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function format_phone($n) {
	$n = preg_replace('/\D*/','',$n);

	//$n = preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $n);
	if (strlen($n) % 2 == 0) {
		$n = preg_replace('/(\d{3})?(\d{3})?(\d{2})?(\d{2})?(\d{2})?(\d{2})?/', '$1-$2 $3 $4 $5 $6', $n, 1);
	} else if (strlen($n) % 2 == 1) {
		$n = preg_replace('/(\d{3})?(\d{2})?(\d{2})?(\d{2})?(\d{2})?(\d{2})?/', '$1-$2 $3 $4 $5 $6', $n, 1);
	}

	return trim($n);
}

/**
 * Returns a phone number anchor.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_phone_link($n) {
	$n = format_phone($n);
	return $n = sprintf('<a href="tel:%s">%s</a>', str_replace(' ', '-', $n), $n);
}

/**
 * Return lowercase first word of top most parent page of current
 * page or supplied page object. This is currently used to set
 * css-classes to determine what section of the website we are on
 * so we can use the correct color for theming.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_section_class_name($item = null) {
	global $post;

	if(!isset($item)) {
		$item = $post;
	}

	$parent = array_reverse(get_post_ancestors($item->ID));

	if(isset($parent[0])) {
		$first_parent = get_page($parent[0]);
	} else {
		$first_parent = $item;
	}

	$title = isset($first_parent->title) ? $first_parent->title : $first_parent->post_title;

	$keyword = strtolower(preg_split("/\ |,\ */", trim($title))[0]);
	$keyword = str_replace(array('å', 'ä', 'ö'), array('a', 'a', 'o'), $keyword);

	return $keyword;
}

