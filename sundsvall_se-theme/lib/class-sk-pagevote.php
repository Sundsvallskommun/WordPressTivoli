<?php
/**
 * "Was this page helpful?"-function for pages.
 */

class SK_PageVote {

	function __construct() {
		add_action( 'sk_after_page_content', array(&$this, 'pagevote_buttons' ), 10);
		add_action( 'wp_ajax_pagevote', array(&$this, 'ajax_vote' ));
	}

	private function get_upvote_percent($post_id) {
		$post_upvotes   = intval(get_post_meta($post_id, 'upvotes', true));
		$post_downvotes = intval(get_post_meta($post_id, 'downvotes', true));

		$total_votes = $post_upvotes + $post_downvotes;

		if(0 >= $total_votes) return 0;

		return round(100 * ($post_upvotes / $total_votes));
	}

	function pagevote_buttons() {
		$post_id = get_queried_object_id();
		$percent = $this->get_upvote_percent($post_id);
		$has_voted = $this->has_voted($post_id);
	?>
		<hr>
		<div class="vote-widget">
			<h2>Blev du hjälpt av sidan?</h2>
			<p>

				<?php if(!$has_voted): ?>
					<button data-vote="up"   class="btn btn-secondary" <?php disabled($has_voted, true); ?>>Ja</button>
					<button data-vote="down" class="btn btn-secondary" <?php disabled($has_voted, true); ?>>Nej</button>
				<?php endif; ?>

				<span class="vote-status">

					<?php if($has_voted): ?>
						<span class="text-success">Tack för din synpunkt!</span>
					<?php endif; ?>

				</span>

				<span class="vote-percent"><?php echo $percent; ?></span>% som röstat blev hjälpt av sidan.

			</p>

			<div class="collapse" id="vote-form">
				<div class="card card-block">
					<?php $feedback_form_id = get_field('page_feedback_form_id', 'option'); ?>
					<?php gravity_form( $feedback_form_id, $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = null, $echo = true ); ?>
				</div>
			</div>

		</div>
	<?php
	}

	function upvote($post_id) {
		$post_upvotes = intval(get_post_meta($post_id, 'upvotes', true));
		return update_post_meta($post_id, 'upvotes', $post_upvotes + 1);
	}

	function downvote($post_id) {
		$post_downvotes = intval(get_post_meta($post_id, 'downvotes', true));
		return update_post_meta($post_id, 'downvotes', $post_downvotes + 1);
	}

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

			setcookie("pagevote_$post_id", 1, 0, '/');

		}

		$percent = $this->get_upvote_percent($post_id);

		$response = array(
			'status' => $status,
			'new_percent' => $percent
		);

		header('Content-type: application/json');

		echo json_encode($response);

		die();
	}

	function has_voted($post_id) {
		return isset($_COOKIE["pagevote_$post_id"]);
	}

}
