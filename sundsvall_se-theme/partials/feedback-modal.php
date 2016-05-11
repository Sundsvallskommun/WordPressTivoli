<?php
$show_feedback_form = get_field('show_site_feedback_form', 'option');
$feedback_form_id = get_field('site_feedback_form_id', 'option');

if($show_feedback_form && $feedback_form_id):
?>

<div class="modal fade gform-async" id="feedbackForm">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Tyck till om webbplatsen</h4>
			</div>

			<div class="modal-body" data-gform="<?php echo $feedback_form_id?>" data-gform-display_description="false" data-gform-display_title="false">
				<?php //gravity_form( $feedback_form_id, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = true, $tabindex = null, $echo = true ); ?>
			</div>

		</div>
	</div>
</div>

<button type="button" class="btn btn-info" data-toggle="modal" data-target="#feedbackForm"
style="position: fixed; top: 50%; right: 0; margin: 0; z-index: 900; transform: rotate(90deg) translateX(50%); transform-origin: top right;">
Tyck till om webbplatsen
</button>

<?php endif; ?>
