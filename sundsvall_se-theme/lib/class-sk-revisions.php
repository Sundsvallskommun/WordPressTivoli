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

	const PUBLISH_DATE_KEY = 'sk_publish_date';
	const REVISION_ID_KEY = '_sk_revision_id';

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
	public function add_save_draft_btn() {
		global $post;
		if ( count( wp_get_post_revisions( $post->ID ) ) >= 1 ) {
			$html = <<<XYZ
			<div id="save-as-draft-action" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); margin-left: -20px;">
				<input name="save-as-draft" type="submit" class="button button-large" id="saveAsPublishedDraft" value="Spara utkast">
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
		//$post_id = (int) $_POST['post_ID'];

		if ( !empty( $_POST['save-as-draft'] ) ) {
			// Check if this post already has a revision id saved.
			if ( empty( get_post_meta( $post_id, self::REVISION_ID_KEY, true ) ) ) {
				// Get revision.
				$revision = $this->get_revision();

				// Save revision id as postmeta.
				update_post_meta( $post_id, self::REVISION_ID_KEY, $revision->ID );
			}
		}

		// If user click preview, we don't do anything
		else if ( isset($_POST['wp-preview'] ) && 'dopreview' == $_POST['wp-preview'] ) {

		// If user clicked save, we assume they want to publish
		// the draft and therefor we'll remove the post meta.
		} else {
			delete_post_meta( $post_id, self::REVISION_ID_KEY );
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
			$revisions = wp_get_post_revisions( $post->ID );
			$current_revision = array_shift( $revisions );
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

		if ( $post && $this->show_revision( $post->ID ) ) {
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
	 * Adds [draft] to the post title if post has a draft.
	 * @param string $title
	 * @param int    $id
	 */
	public function add_draft_to_title( $title, $id ) {
		if ( is_admin() && $this->show_revision( $id ) ) {
			$title .= ' [Opublicerad version]';
		}

		return $title;
	}

	/**
	 * Add metabox to draft posts to let user pick a publishing date.
	 * @return void
	 */
	public function add_publishing_metabox() {
		if ( $this->show_revision() ) {
			add_meta_box(
				'sk_publishing_metabox',
				'Publiceringsdatum',
				array( $this, 'sk_publishing_metabox_callback' ),
				'page',
				'side',
				'high'
			);
		}
	}

	/**
	 * Enqueue scripts and styles for jquery ui datepicker.
	 * @return void
	 */
	public function datepicker_scripts() {
		if ( $this->show_revision() ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		}
	}

	/**
	 * Add datepicker form with custom validation.
	 * @return void
	 */
	public function sk_publishing_metabox_callback( $post ) { 

		wp_nonce_field( 'sk_publish_date_nonce', 'sk_nonce' ); ?>

		<form action="" method="post">

			<?php $expiry_date = get_post_meta( $post->ID, self::PUBLISH_DATE_KEY, true ); ?>

		<p>
			<label for="sk_publish_date">
				<?php _e( 'Uppdateringsdatum', 'sundsvall_se' ); ?>
			</label>
		</p>

		<p> <input type="text" class="publishing-date" id="sk_publish_date" name="sk_publish_date" value="<?php echo esc_attr( $expiry_date ); ?>" / > </p>

		<p><small>Välj ett datum för att publicera den senaste versionen.</small></p>

				<script type="text/javascript">
						jQuery(document).ready(function() {
								jQuery('.publishing-date').datepicker({
										dateFormat : 'yy-mm-dd',
										minDate : '+1'
								});
						});

						jQuery(document).ready(function($){
								$('#post').submit(function(){

									var $dateInput = $('.publishing-date');
									var dateValue = $dateInput.val();

									var date = new Date(dateValue);

									var m3 = new Date();
									m3.setMonth( m3.getMonth() + 3 );

								});

								function invalidDate() {
									$('.publishing-date').css('border-color', 'red');

									return false;

								}
						});


				</script>
		</form>

	<?php }

	/**
	 * Saves publishing date as postmeta.
	 * @param  integer $post_id
	 * @return void
	 */
	public function save_publishing_meta( $post_id ) {
		// Don't do anything if nonce is invalid or user is not allowed
		// to save.
		if ( ! isset( $_POST[ 'sk_nonce' ] )
			|| ! wp_verify_nonce( $_POST[ 'sk_nonce' ], 'sk_publish_date_nonce' )
			|| ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if publish_date is set in post data.
		if ( isset( $_POST['sk_publish_date'] ) ) {
			$publish_date = ( $_POST['sk_publish_date'] );

			update_post_meta( $post_id, self::PUBLISH_DATE_KEY, $publish_date );
		}
	}

	/**
	 * Adds our action to the WP-CRON job.
	 * @return void
	 */
	public function publish_content_cron() {
		if ( ! wp_next_scheduled( 'sk_unpublish_expired' ) ) {
			wp_schedule_event( time(), 'hourly', 'sk_unpublish_expired' );
		}
	}

	/**
	 * Replaces current content with draft.
	 * @return void
	 */
	public function publish_content() {
		$today = date('Y-m-d');

		$query_args = array(
				'meta_key'		=> self::PUBLISH_DATE_KEY,
				'meta_value'	=> $today,
				'meta_compare'	=> '<=',
				'meta_type'		=> 'DATE',
				'post_type'		=> 'any'
		);

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				// Remove all sk_revision metadata.
				delete_post_meta( get_the_ID(), self::REVISION_ID_KEY );
				delete_post_meta( get_the_ID(), self::PUBLISH_DATE_KEY );
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

			if(!$post) {
				return false;
			}

			$post_id = $post->ID;
		}
		return !empty( get_post_meta( $post_id, self::REVISION_ID_KEY, true ) );
	}

	/**
	 * Helper function for retrieving the post revision.
	 * @return WP_Post
	 */
	private function get_revision( $post_id = null ) {

		if ( $this->revision === null ) {
			global $post;
			if ( $post_id === null || !$post ) {
				$post_id = $post->ID;
			}

			// Check if revision id exists.
			if ( !empty( get_post_meta( $post_id, self::REVISION_ID_KEY, true ) ) ) {
				$this->revision = get_post( get_post_meta( $post_id, self::REVISION_ID_KEY, true ) );
			}

			else {
				// Get revisions.
				$revisions = wp_get_post_revisions( $post_id );

				// Make sure we don't point at an autosave.
				foreach( $revisions as $key => $revision ) {
					if ( wp_is_post_autosave($revision->ID) ) {
						unset($revisions[$key]);
					}
				}

				$revisions = array_slice( $revisions, 1, 1 );

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

// Add filter to let WordPress save an unlimited number of revisions
// if a post is marked as having a sk_revision.
add_filter( 'wp_revisions_to_keep', 'unlimited_revisions_for_draft', 500, 2 );

// Adds a [draft] to the title when in back-end.
add_filter( 'the_title', array( SK_Revisions::get_instance(), 'add_draft_to_title' ), 10, 2 );

// Adds a [draft] to the title when in back-end.
add_filter( 'the_title', array( SK_Revisions::get_instance(), 'add_draft_to_title' ), 10, 2 );

// Add a meta box for choosing a publishing date.
// Also enqueue datepicker to make it easier for user.
add_action('admin_enqueue_scripts', array( SK_Revisions::get_instance(), 'datepicker_scripts' ) );
add_action('add_meta_boxes', array( SK_Revisions::get_instance(), 'add_publishing_metabox' ) );

// Add action to save_post to save publishing date for revision.
add_action('save_post', array( SK_Revisions::get_instance(), 'save_publishing_meta' ) );

// Add CRON actions to publish draft when publishing date occurs.
add_action( 'sk_publish_content', array( SK_Revisions::get_instance(), 'publish_content' ) );
add_action('init', array( SK_Revisions::get_instance(), 'publish_content_cron' ) );

/**
 * Don't limit number of revisions to keep when a published post has a draft
 * revision.
 */
function unlimited_revisions_for_draft( $num, $post ) {

	if ( !empty( get_post_meta( $post->ID, SK_Revisions::REVISION_ID_KEY, true ) ) ) {
		return -1;
	}

	// Return default num, in default installs this function would not do
	// anything because this is set to -1.
	//
	// If WP_POST_REVISIONS is set to a number in wp-config (or another filter)
	// $num would be that number.
	return $num;

}
