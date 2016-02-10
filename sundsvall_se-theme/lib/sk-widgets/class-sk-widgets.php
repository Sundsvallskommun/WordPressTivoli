<?php
/**
 *
 */
class SK_Widgets {

	function __construct() {
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_start'), 1);
		add_action('sk_page_widgets', array(&$this, 'widget_google_map'));
		add_action('sk_page_widgets', array(&$this, 'page_widgets_wrapper_end'), 9000);
	}

	function page_widgets_wrapper_start() {
		echo '<aside class="page-widgets">';
	}

	function page_widgets_wrapper_end() {
		echo '</aside>';
	}

	function widget_google_map() {

		if(!get_field('map_show')) return false;

		if( have_rows('locations') ):

			wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp', false);

		?>

		<div class="page-widget widget-map">

			<div class="page-widget__container">

				<div class="map-description">
					<h3 class="page-widget__title"><?php the_field('map_heading'); ?></h3>
					<p class="page-widget__description"><?php the_field('map_description'); ?></p>
				</div>

				<div class="map-container acf-map">

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

			</div>

		</div>
<?php endif;

	}
}
