<?php
/**
 * "Was this page helpful?"-function for pages.
 *
 * To get a notification email sent to the author of the post, create an
 * email-notification named "Författarnotis" and the email field will be
 * filtered to post author email.
 */
class SK_PageVote {

	const COOKIE_BASE_NAME = 'skpv';

	function __construct() {

		add_action('sk_page_helpmenu', array(&$this, 'display_vote_percent'), 40);

		add_action( 'sk_after_page_content', array(&$this, 'pagevote_buttons' ), 20);

		add_action('wp_ajax_pagevote', array(&$this, 'ajax_vote'), 10);
		add_action('wp_ajax_nopriv_pagevote', array(&$this, 'ajax_vote'), 10);

		// Field from ACF settings page.
		$this->feedback_form_id = get_field('page_feedback_form_id', 'option');

		add_filter( "gform_notification_$this->feedback_form_id", array(&$this, 'notify_author'), 10, 3);

	}

	/**
	 * Overwrite email address of gravity forms notification named
	 * "Författarnotis". Send it to post author of post where form was filled.
	 */
	function notify_author($notification, $form, $entry) {

		if ( 'Författarnotis' != $notification['name']) {
			return $notification;
		}

		global $post;

		$author_id = $post->post_author;

		$notification['to'] = get_the_author_meta('email', $author_id);

		return $notification;

	}

	/**
	 * Get percentage of upvotes for page.
	 *
	 * @param int $post_id
	 */
	private function get_upvote_percent($post_id) {
		$post_upvotes   = intval(get_post_meta($post_id, 'upvotes', true));
		$post_downvotes = intval(get_post_meta($post_id, 'downvotes', true));

		$total_votes = $post_upvotes + $post_downvotes;

		if(0 >= $total_votes) return false;

		return round(100 * ($post_upvotes / $total_votes));
	}

	/**
	 * Get text describing upvote percent.
	 *
	 * @param int $post_id
	 */
	private function get_upvote_percent_text($post_id) {

		$percent = $this->get_upvote_percent($post_id);

		if(false === $percent) {
			return '';
		}

		return "$percent% blev hjälpt av den här sidan.";

	}

	function display_vote_percent() {
		global $sk_helpmenu;
		$post_id = get_queried_object_id();

		$percentText = $this->get_upvote_percent_text($post_id);

		if(!empty($percentText)) {
			$linkContent = sprintf('<span class="vote-percent">%s</span>', $percentText);
			echo $sk_helpmenu->helplink('exclamation-sign', '#pageVote', $linkContent);
		}
	}

	/**
	 * Show buttons if visitor has not voted on this session.
	 */
	function pagevote_buttons() {
		$post_id = get_queried_object_id();
		$has_voted = $this->has_voted($post_id);

		if(is_front_page() || is_search() || is_navigation()) {
			return;
		}

	?>
		<div id="pageVote" class="vote-widget">
			<h2 class="pull-xs-left vote-widget__heading">Var sidan till hjälp?</h2>

			<p class="vote-widget__actions pull-xs-left">

				<?php if(!$has_voted): ?>
					<button data-vote="up"   class="btn btn-secondary" <?php disabled($has_voted, true); ?>>Ja</button>
					<button data-vote="down" class="btn btn-secondary" <?php disabled($has_voted, true); ?>>Nej</button>
				<?php endif; ?>

				<span class="vote-status">

					<?php if($has_voted): ?>
						<span class="">Du har redan tyckt till om denna sida, tack för din synpunkt!</span>
					<?php endif; ?>

				</span>

			</p>

			<div class="clearfix"></div>
			<div class="collapse" id="vote-form">
				<div class="card card-block" data-gform="<?php echo $this->feedback_form_id; ?>">
					<?php gravity_form( $this->feedback_form_id, $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = null, $echo = true ); ?>
				</div>
			</div>

		</div>
	<?php
	}

	/**
	 * Register an upvote for post/page
	 *
	 * @param int $post_id
	 */
	private function upvote($post_id) {
		$post_upvotes = intval(get_post_meta($post_id, 'upvotes', true));
		return update_post_meta($post_id, 'upvotes', $post_upvotes + 1);
	}

	/**
	 * Register a downvote for post/page
	 *
	 * @param int $post_id
	 */
	private function downvote($post_id) {
		$post_downvotes = intval(get_post_meta($post_id, 'downvotes', true));
		return update_post_meta($post_id, 'downvotes', $post_downvotes + 1);
	}

	/**
	 * Check if user has voted for post in current session.
	 *
	 * @param int $post_id
	 */
	private function has_voted($post_id) {
		return isset($_COOKIE[self::COOKIE_BASE_NAME.$post_id]);
	}

	/**
	 * Handle voting through ajax
	 */
	function ajax_vote() {


		check_ajax_referer( 'page-vote', false );

		$post_id   = intval(sanitize_text_field($_POST['post_id']));
		$vote_type = sanitize_text_field($_POST['vote_type']);
		$status = 'success';

		if($this->has_voted($post_id)) {

			$status = 'error';

		} else {


			if('up' === $vote_type) {
				$this->upvote($post_id);
			} else if('down' === $vote_type) {
				$this->downvote($post_id);
			}

			setcookie(self::COOKIE_BASE_NAME.$post_id, 1, 0, '/');

		}

		$percent_text = $this->get_upvote_percent_text($post_id);

		$response = array(
			'status' => $status,
			'new_percent_text' => $percent_text
		);

		header('Content-type: application/json');

		echo json_encode($response);

		die();
	}

}
