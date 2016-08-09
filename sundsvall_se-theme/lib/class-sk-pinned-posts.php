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
				$title .= ($title !== $original_title) ? '…' : '.';

				$remaining_limit = $maxlength - $title_length;

				$original_excerpt = sk_get_excerpt();

				// Limit length of excerpt based on remaining limit after title.
				$excerpt = ($remaining_limit > 0) ? substr($original_excerpt, 0, $remaining_limit) : '';

				// Add ellipsis at the end of excerpt if it has been limited.
				$excerpt .= ($excerpt !== $original_excerpt && strlen($excerpt)) ? '…' : '';

				?>
				<strong><?php echo $title; ?></strong>
				<?php echo $excerpt; ?> »
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
			<span>
				<?php the_icon('error'); ?>
				<strong><?php the_title(); ?>.</strong>
				<?php echo sk_get_excerpt(); ?> »
			</span>
		</a>

		<?php
		endforeach;
		echo '</div>';

		wp_reset_postdata();
	}

}
