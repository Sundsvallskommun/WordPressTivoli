<?php
/**
 * Post and page language
 * 
 * Handles some actions regarding different languages on posts and pages
 * such as notifiying authors when posts have changed.
 *
 * @since 1.0.0
 */

class SK_Language {

	/**
	 * Binds our actions to WP.
	 */
	function __construct() {
		// Update author on post save.
		add_action( 'save_post', array( $this, 'update_author_on_update' ) );

		// Add link to finnish translation.
		add_action( 'sk_page_helpmenu', array( $this, 'translated_version_button' ), 11 );
	}

	/**
	 * @param  integer ID of updated/saved post
	 * @return void
	 */
	public function notify_author_on_update( $post_id ) {

		// Don't update author if this is a revision or
		// if this isn't a swedish post.
		if ( wp_is_post_revision( $post_id ) || !$this->is_swedish_post( $post_id ) )
			return;


		// Check if there's any posts that are translated from this one.
		$translated_posts = $this->get_translated_posts( $post_id );
		if ( $translated_posts ) {
			foreach ( $translated_posts as $trans_post ) {
				// Get author email.
				$author_email = get_the_author_meta( 'email', $trans_post->post_author );

				// Build email.
				$subject = 'Sundsvalls Kommun: Uppdaterat originalinlägg';
				$body = sprintf( __( 'Orignalinlägget för din översatta version av <a href="%s">%s</a> har blivit uppdaterat.<br><br>Klicka <a href="%s">här</a> för att se din version.', 'sundsvall_se' ),
					get_permalink( $post_id ),
					get_the_title( $post_id ),
					get_permalink( $trans_post->ID ) );
				$headers = array(
					'Content-Type: text/html'
				);

				// Send it.
				wp_mail( $author_email, $subject, $body, $headers );
			}
		}

	}

	/**
	 * Adds a link to any (and all) translated versions of this
	 * particular post / page to the help menu.
	 * @return void
	 */
	public function translated_version_button() {
		global $post;

		// Return if it's not a swedish post.
		if ( !$this->is_swedish_post( $post->ID ) )
			return;

		// Check if there are any translated versions.
		$translated_posts = $this->get_translated_posts( $post->ID );
		if ( $translated_posts ) {
			foreach ( $translated_posts as $translated_post ) {
				$lang_code = get_field( 'lang', $translated_post->ID );
				echo SK_Helpmenu::helplink( 'listen', get_permalink( $translated_post->ID ), sprintf( __( 'Läs på %s', 'sundsvall_se' ), $this->get_full_name( $lang_code ) ) );
			}
		}
	}

	/**
	 * Checks ACF if post is swedish or not.
	 * @param  integer ID of post
	 * @return boolean
	 */
	private function is_swedish_post( $post_id ) {

		return get_field( 'lang', $post_id ) === 'sv';

	}

	/**
	 * Returns any translated posts or pages 
	 * @param  integer
	 * @return array|boolean
	 */
	private function get_translated_posts( $post_id ) {

		// Query WP for translated posts.
		$args = array(
			'meta_key'		=> 'original_post',
			'meta_value'	=> (int) $post_id,
			'nopaging'		=> true,
			'post_type'		=> array( 'post', 'page' ),
			'post_status'	=> 'published'
		);
		$translated_posts = get_posts( $args );

		// Either return array of WP_Post or false if
		// no posts were found.
		if ( !empty( $translated_posts ) )
			return $translated_posts;
		else
			return false;

	}

	/**
	 * @param  string ISO 639-1 language code
	 * @return string Literal name for specified language code
	 */
	private function get_full_name( $language_code ) {
		switch ( $language_code ) {
			case 'sv':
				return 'svenska';
			case 'fi':
				return 'finska';
		}
	}

}

/**
 * *=========================================*
 *       THEME FUNCTIONS AND HELPERS
 * *=========================================*
 */

/**
 * Echoes an apporiopate html lang="" attribute based on what language
 * current post is written in.
 * @return void
 */
function sk_lang_attr() {
	global $post;
	if ( $post ) {
		printf( 'lang="%s"', get_field( 'lang', $post->ID ) );
	}
}