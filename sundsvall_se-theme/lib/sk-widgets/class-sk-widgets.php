<?php
/**
 * General/minor widgets and related functions
 */
class SK_Widgets {

	function __construct() {
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_start'), 1);
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_end'), 9000);

		add_action('init', array(&$this, 'misc_tinymce_buttons_init'));
		add_action('sk_page_widgets', array(&$this, 'widget_google_map'));
	}

	function page_widgets_wrapper_start() {
		echo '<aside class="page-widgets">';
	}

	function page_widgets_wrapper_end() {
		echo '</aside>';
	}

	/**
	 * Add custom buttons to TinyMCE
	 *
	 * @author Johan Linder <johan@flatmate.se>
	 */
	function misc_tinymce_buttons_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
			return;
		}

		add_filter('mce_buttons', array(&$this, 'register_tinymce_misc_buttons'));
		add_filter('mce_external_plugins', array(&$this, 'add_tinymce_misc_buttons_plugin'));
	}

	function register_tinymce_misc_buttons($buttons) {
		$buttons[] = "sk_misc_buttons";
		return $buttons;
	}

	function add_tinymce_misc_buttons_plugin($plugin_array) {
		$plugin_array['sk_misc_buttons'] = get_template_directory_uri().'/lib/sk-widgets/sk_misc_buttons.js';
		return $plugin_array;
	}

	function widget_google_map() {

		if(!get_field('map_show')) return false;

		if( have_rows('map_locations') ):

			wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp', false);

		?>

		<div class="page-widget widget-map">

			<div class="page-widget__container">

				<div class="page-widget__main acf-map">

				<?php while ( have_rows('map_locations') ) : the_row(); 
					$location = get_sub_field('location');
				?>
					<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
						<h4><?php the_sub_field('location_title'); ?></h4>
						<p class="address"><?php echo $location['address']; ?></p>
						<p><?php the_sub_field('location_description'); ?></p>
					</div>
				<?php endwhile; ?>

				</div>

				<div class="page-widget__secondary">
					<h3 class="page-widget__title"><?php the_field('map_heading'); ?></h3>
					<div class="page-widget__description"><?php the_field('map_description'); ?></div>
				</div>

			</div>

		</div>
<?php endif;

	}

}
