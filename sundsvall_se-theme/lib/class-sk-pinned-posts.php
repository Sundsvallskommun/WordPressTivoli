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

		add_action( 'sk_header_end', array(&$this, 'display_global_posts'), 10);
		add_action( 'sk_after_page_title', array(&$this, 'display_pinned_posts'), 10);
	}

	function display_global_posts() {

		$args = array( 
			'post_type' => $this->pinnable_post_types,
			'orderby'   => 'modified',
			'meta_query' => array(
				array(
					'key' => 'pin_post_global',
					'value' => 1,
					'compare' => 'LIKE'
				)
			)
		);
		echo '<div class="global-posts">';

		global $post;
		$posts = get_posts( $args );
		foreach ( $posts as $post ) :
			setup_postdata( $post );

			$post_type = get_post_type( get_the_ID() );
			$alert_type = $post_type === 'service_message' ? 'warning' : 'info';
		?>

		<a class="alert alert-centered <?php echo "alert-$alert_type"; ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<span>
			<?php
				if($post_type === 'service_message') {
					the_icon('exclamation-sign');
				}
				the_title();
			?>
			</span>
		</a>

		<?php
		endforeach;

		echo '</div>';

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

		echo '<div class="pinned-posts">';

		global $post;
		$posts = get_posts( $args );
		foreach ( $posts as $post ) :
			setup_postdata( $post );

			$post_type = get_post_type( get_the_ID() );
			$alert_type = $post_type === 'service_message' ? 'warning' : 'info';
		?>

		<a class="alert <?php echo "alert-$alert_type"; ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php
				if($post_type === 'service_message') {
					the_icon('exclamation-sign');
				}
				the_title();
			?>
		</a>

		<?php
		endforeach;
		echo '</div>';

		wp_reset_postdata();
	}

}
