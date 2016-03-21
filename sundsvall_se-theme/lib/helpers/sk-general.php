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
 * Format phone number/numbers with dash and spaces. Separate multiple numbers
 * by comma.
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @return array
 */
function format_phone($n) {
	$numbers = explode(',', $n);

	foreach($numbers as $key => $num) {

		$num = preg_replace('/\D*/','',$num);

		//$n = preg_replace('/(\d{3})(\d{3})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $n);
		if (strlen($num) % 2 == 0) {
			$num = preg_replace('/(\d{3})?(\d{3})?(\d{2})?(\d{2})?(\d{2})?(\d{2})?/', '$1-$2 $3 $4 $5 $6', $num, 1);
		} else if (strlen($num) % 2 == 1) {
			$num = preg_replace('/(\d{3})?(\d{2})?(\d{2})?(\d{2})?(\d{2})?(\d{2})?/', '$1-$2 $3 $4 $5 $6', $num, 1);
		}

		$numbers[$key] = trim($num);
	}

	return $numbers;
}

/**
 * Returns a phone number anchor.
 *
 * @author Johan Linder <johan@flatmate.se>
 */
function get_phone_links($n) {
	$numbers = format_phone($n);
	$str = '';
	$i = 0;
	foreach($numbers as $num) {
		if($i > 0) {
			$str .= ', ';
		}
		$str .= sprintf('<a href="tel:%s">%s</a>', str_replace(' ', '-', $num), $num);
		$i += 1;
	}
	return $str;
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

/**
 * Recursive function to get the closest ancestor that has the correct
 * field values.
 *
 * @author Johan Linder <johan@flatmate.se>
 *
 * @param $page_id Page to find ancestor of.
 * @param array $fields fields and their expected value as $field => $value.
 *
 * @return bool|int returns page id if all fields have the expected value. Else false.
 */
function ancestor_field($page_id, array $fields) {

	$parent_id = wp_get_post_parent_id($page_id);

	if(!$parent_id) {
		return false;
	}

	foreach($fields as $field => $value) {
		if(get_field($field, $parent_id) != $value) {
			return ancestor_field($parent_id, $fields);
		}
	}

	return $parent_id;
}

/**
 * Get excerpt by post/page-id
 *
 * @param int $post_id
 *
 * @return string
 */
function sk_get_excerpt($post_id = 0) {
	global $post;
	$save_post = $post;
	$post = get_post( $post_id );
	setup_postdata( $post );
	$excerpt = get_the_excerpt();
	$post = $save_post;
	wp_reset_postdata( $post );
	return $excerpt;
}

