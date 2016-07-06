<?php
/**
 * Revisions
 *
 * Adds the option to show a previous revision based on a
 * publishing date set on each post.
 *
 * @since  1.0.0
 */

class SK_Revisions {

	/**
	 * Singleton instance of class.
	 * @var SK_Vacancy|null
	 */
	private static $instance = null;

	/**
	 * The revision we should show.
	 * @var WP_Post|null
	 */
	private $revision = null;

	/**
	 * Private constructor to disallow instanciating.
	 */
	private function __construct() {}

	/**
	 * Returns singleton instance.
	 * @return SK_Easycruit_Wrapper
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add our save as draft button to edit screens.
	 * @param  WP_Post
	 * @return void
	 */
	public function add_save_draft_btn( $post ) {
		if ( count( wp_get_post_revisions( $post->ID ) ) >= 1 ) {
			$html = <<<XYZ
			<div id="save-as-draft-action" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); margin-left: -20px;">
				<input name="save-as-draft" type="submit" class="button button-large" id="publish" value="Spara utkast">	
			</div>
XYZ;
			echo $html;
		}
	}

	/**
	 * Checks if user clicked save as draft and then saves previous revision
	 * as post meta.
	 * @param  integer
	 * @return void
	 */
	public function save_revision_id( $post_id ) {
		$post_id = (int) $_POST['post_ID'];

		if ( !empty( $_POST['save-as-draft'] ) ) {
			// Check if this post already has a revision id saved.
			if ( empty( get_post_meta( $post_id, 'sk_revision_id', true ) ) ) {
				// Get revision.
				$revision = $this->get_revision();

				// Save revision id as postmeta.
				update_post_meta( $post_id, 'sk_revision_id', $revision->ID );
			}
		}

		// If user clicked save, we assume they want to publish
		// the draft and therefor we'll remove the post meta.
		else {
			delete_post_meta( $post_id, 'sk_revision_id' );
		}
	}

	/**
	 * Shows user editing a notice that they are editing a future draft and
	 * not actually the published content.
	 * @return void
	 */
	public function show_draft_notice() {
		if ( $this->show_revision() ) {
			global $post;
			$current_revision = array_shift( wp_get_post_revisions( $post->ID ) );
			$saved_revision = $this->get_revision( $post->ID );
			$revision_edit_link = sprintf( 'Klicka <a href="%s/revision.php?from=%d&to=%d">här</a> för att jämföra utkastet med versionen som visas.', admin_url(), $current_revision->ID, $saved_revision->ID );
			$html = <<<XYZ
			<div class="notice notice-info">
				<p>Du redigerar ett utkast.</p>
				<p>{$revision_edit_link}</p>
			</div>
XYZ;

			echo $html;
		}
	}

	/**
	 * If this is a future draft, change it to the revision post.
	 * @param  WP_Post
	 * @return WP_Post
	 */
	public function change_post_to_revision( $wp_query ) {
		if ( is_admin() )
			return;

		global $post;

		if ( $this->show_revision( $post->ID ) ) {
			// Get revision.
			$revision = $this->get_revision();

			// Modify global $post.
			// TODO:
			// Find a better way to do this.
			foreach ( $post as $key => $value ) {
				$post->$key = $revision->$key;
			}
		}
	}

	/**
	 * Helper function to check if we're suppose to show
	 * revision instead of post.
	 * @return boolean
	 */
	private function show_revision( $post_id = null ) {
		if ( $post_id === null ) {
			global $post;
			$post_id = $post->ID;
		}
		return !empty( get_post_meta( $post_id, 'sk_revision_id', true ) );
	}

	/**
	 * Helper function for retrieving the post revision.
	 * @return WP_Post
	 */
	private function get_revision( $post_id = null ) {
		if ( $this->revision === null ) {
			if ( $post_id === null || !$post ) {
				global $post;
				$post_id = $post->ID;
			}

			// Check if revision id exists.
			if ( !empty( get_post_meta( $post_id, 'sk_revision_id', true ) ) ) {
				$this->revision = get_post( get_post_meta( $post_id, 'sk_revision_id', true ) );
			}

			else {
				// Get revisions.
				$revisions = array_slice( wp_get_post_revisions( $post_id ), 1, 1 );

				// Save last revision.
				$this->revision = $revisions[0];
			}
		}

		// Return revision.
		return $this->revision;
	}

}

/**
 * *=========================================*
 *              MISCELLANEOUS
 * *=========================================*
 */

/**
 * Register our actions, filters, shortcodes and scripts.
 */

// Add our save as draft button.
add_action('post_submitbox_start', array( SK_Revisions::get_instance(), 'add_save_draft_btn' ) );

// Add action to save_post to intercept revisions.
add_action( 'save_post', array( SK_Revisions::get_instance(), 'save_revision_id' ) );

// Show admin notice if a draft is saved.
add_action( 'admin_notices', array( SK_Revisions::get_instance(), 'show_draft_notice' ) );

// Change global $post to reference revision WP_Post.
add_action( 'wp', array( SK_Revisions::get_instance(), 'change_post_to_revision' ) );