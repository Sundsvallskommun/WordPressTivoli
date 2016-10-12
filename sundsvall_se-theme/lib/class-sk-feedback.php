<?php
/**
 * Feedback form for pages
 */
class SK_Feedback {

	function __construct() {

		add_action( 'sk_after_page_content', array(&$this, 'feedback_form' ), 40);

		// Field from ACF settings page.
		$this->feedback_form_id = get_field('page_feedback_form_id', 'option');

	}

	/**
	 * Feedback form behind button after page content.
	 */
	function feedback_form() {

		if ( !$this->feedback_form_id ) return false;

		$post_id = get_queried_object_id();

		if(is_front_page() || is_search() || is_navigation()) {
			return;
		}

		if ( ! class_exists( 'GFForms' ) ) {
			return;
		}

		// Only show if form id is an actual form
		if( !GFAPI::get_form($this->feedback_form_id )) {
			return false;
		}

	?>
		<div class="feedback-widget">
			<p class="feedback-widget__actions pull-xs-left">

			<button data-toggle="collapse" data-target="#feedback-form" class="btn btn-secondary">Tyck till om sidan</button>

			</p>

			<div class="clearfix"></div>
			<div class="collapse" id="feedback-form">
				<div class="card card-block" data-gform="<?php echo $this->feedback_form_id; ?>">
					<?php gravity_form( $this->feedback_form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = null, $echo = true ); ?>
				</div>
			</div>

		</div>
	<?php
	}

}
