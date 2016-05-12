<?php
/**
 * Service status messages post type and display functions. Used as service messages for
 * pages.
 *
 * @author Therese Persson
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Pinned_Posts {

	function __construct() {

		$this->pinnable_post_types = array('post', 'service_message');

		add_action( 'sk_pinned_posts', array(&$this, 'display_pinned_posts'));
	}

	function display_pinned_posts() {

		$args = array( 
			'post_type' => $this->pinnable_post_types,
			'orderby'   => 'modified',
			'meta_query' => array(
				array(
					'key' => 'pin_post_on',
					'value' => '"' . get_the_ID() . '"',
					'compare' => 'LIKE'
				)
			)
		 );

		echo '<div class="pineed-posts">';

		global $post;
		$posts = get_posts( $args );
		foreach ( $posts as $post ) :
			setup_postdata( $post );

			$post_type = get_post_type( get_the_ID() );
			$alert_type = $post_type === 'service_message' ? 'warning' : 'info';
		?>

		<div class="alert <?php echo "alert-$alert_type"; ?>">
			<a class="alert-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php
				if($post_type === 'service_message') {
					the_icon('exclamation-sign');
				}
				the_title();
			?>
			</a>
		</div>

		<?php
		endforeach;
		echo '</div>';

	}

}
