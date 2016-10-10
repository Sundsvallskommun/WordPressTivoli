<?php
/**
 * Service status messages post type and display functions.
 *
 * @since 1.0.0
 */

class SK_Pinned_Posts {

	function __construct() {

		$this->pinnable_post_types = array('post', 'service_message');

		add_action( 'sk_header_end', array(&$this, 'display_global_posts'), 10);
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

		<a class="alert <?php echo "alert-$alert_type"; ?>" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<span>
				<?php the_icon('error'); ?>
				<?php
				/**
				 * Limit title and excerpt to 130 characters in pinned posts
				 */
				$maxlength = 130;

				$original_title   = get_the_title();

				$title_length = strlen($original_title);

				// Limit length of title
				$title = substr($original_title, 0, $maxlength);

				// Add ellipsis at the end of title if it has been limited.
				$title .= ($title !== $original_title) ? '…' : '';

				echo $title;
				?>
			</span>
		</a>

		<?php
		endforeach;

		echo '</div>';

	}

}
