<?php

/**
 * Class SK_Share - Share post on social media.
 */
class SK_Share {

	private $media = array( 'facebook' => '', 'linkedin' => '', 'twitter' => '' );
	private $checked = false;

	/**
	 * SK_Share constructor.
	 */
	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

	}

	public function init() {

		// check if share module is activated.
		$activated = get_field( 'sk_share_activated', 'options' );

		if ( ! $activated ) {
			return false;
		}

		// check if share module should be auto checked as default
		$checked = get_field( 'sk_share_checked', 'options' );
		if ( $checked ) {
			$this->checked = true;
		}


		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10 );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'sk_page_helpmenu', array( $this, 'share_link' ), 100 );
	}

	/**
	 * Adding meta boxes to pages and posts.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sk-share', __( 'Dela', 'sk_tivoli' ), array(
			$this,
			'sk_share_callback'
		), array( 'page', 'post'), 'side', 'low' );
	}

	/**
	 * Content for meta box.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function sk_share_callback() {
		global $post;
		$post_id        = $post->ID;
		$sk_share_media = get_post_meta( $post_id, '_sk_share_media', true );
		$previous_value = ! empty( $sk_share_media ) ? $sk_share_media : array();

		if( $this->checked === true ) {
			// mark all as checked for a new post.
			if ( $post->post_status == 'auto-draft' ) {
				foreach ( $this->media as $key => $media ) {
					$previous_value[] = $key;
				}
			}
		}

		?>
		<p class="desc"><?php _e( 'Välj vilka sociala medier denna post ska vara möjlig att dela på.', 'sk_tivoli' ); ?></p>
		<?php foreach ( $this->media as $key => $media ) : ?>
			<p><label><input type="checkbox"
			                 name="sk_share_media[]" <?php checked( in_array( $key, $previous_value ) ? $key : null, $key, true ); ?>
			                 value="<?php echo $key; ?>"><?php printf( __( '%s', 'sk_tivoli' ), ucfirst( $key ) ); ?>
				</label></p>
			<?php
		endforeach;
		wp_nonce_field( __FILE__, 'sk_share_meta_box' );
	}

	/**
	 * Saving post meta for share.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function save_post( $post_id ) {

		// Bail if we're doing an auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		// if our nonce isn't there, or we can't verify it, bail
		if ( ! isset( $_POST['sk_share_meta_box'] ) || ! wp_verify_nonce( $_POST['sk_share_meta_box'], __FILE__ ) ) {
			return false;
		}

		if ( isset( $_POST['sk_share_media'] ) ) {
			$media = $_POST['sk_share_media'];
		}

		if ( empty( $media ) ) {
			delete_post_meta( $post_id, '_sk_share_media' );
		} else {
			update_post_meta( $post_id, '_sk_share_media', $media );
		}

	}

	/**
	 * Create the share link in sidebar.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function share_link() {
		global $post;

		$shares = get_post_meta( $post->ID, '_sk_share_media', true );

		if ( ! empty( $shares ) ) {
			foreach ( $shares as $share ) {
				echo SK_Helpmenu::helplink( $share, $this->get_share_url( get_permalink( $post->ID ), $share ), sprintf( __( 'Dela på %s', 'sk_tivoli' ), ucfirst( $share ) ) );
			}
		}

	}

	/**
	 * Generate the unique share url for the media.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $url
	 * @param $media
	 *
	 * @return string
	 */
	public function get_share_url( $url, $media ) {
		global $post;

		$title = urlencode( get_the_title( $post->ID ) );
		$url   = urlencode( $url );

		if ( $media === 'facebook' ) {
			return sprintf( 'https://www.facebook.com/sharer.php?u=%s&t=%s', $url, $title );
		} elseif ( $media === 'linkedin' ) {
			return sprintf( 'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s&summary=&source=', $url, $title );
		} elseif ( $media === 'twitter' ) {
			return sprintf( 'https://twitter.com/share?url=%s', $url );
		} else {
			return '#';
		}

	}


}