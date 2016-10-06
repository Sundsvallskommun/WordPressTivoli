<?php
/**
 * General/minor widgets and related functions
 */
class SK_Widgets {

	function __construct() {
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_start'), 1);
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_end'), 9000);

		add_action('sk_page_widgets', array(&$this, 'widget_google_map'));
	}

	function page_widgets_wrapper_start() {
		echo '<aside class="page-widgets">';
	}

	function page_widgets_wrapper_end() {
		echo '</aside>';
	}

	function widget_google_map() {

		if(!get_field('map_show')) return false;

		if( have_rows('map_locations') ):

			wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&?key=AIzaSyCC1vUDAh4hf_orwNHREJ85i_yFApHkB20', false);

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
