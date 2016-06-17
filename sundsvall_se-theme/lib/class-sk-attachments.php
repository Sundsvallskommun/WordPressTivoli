<?php
/**
 * Attachment settings and extra fields.
 *
 * @since 1.0.0
 *
 * @package sundsvall_se
 */

class SK_Attachments {

	function __construct() {
		$this->attachment_fields();

		add_action('admin_head', array(&$this, 'validate_media_javascript'));
	}

	/**
	 * Don't allow insertion of images without alt and photographer.
	 */
	function validate_media_javascript() {
?>
		<script>

		jQuery(function($){

			// Add media button
			$('.wp-media-buttons .add_media').on( 'click', function( event ){

						// Original send function to call if image should be inserted
						var original_send = wp.media.editor.send.attachment;

						// New send function where we validate alt and photographer
						wp.media.editor.send.attachment = function( props, attachment ) {

							// Only check imagees, if its any other attachment type, call
							// original send function.
							if('image' !== attachment.type) {
								return original_send.apply(this, arguments);
							}

							// Store all empty fields we care about
							var missing = [];

							!attachment.alt && missing.push('alt-text');
							!attachment.photographer && missing.push('fotograf');

							// Show error message if there is one, else call original send
							// function.
							if(missing.length) {

								var error_message = 'Följande obligatoriska fält saknas: ';

								for(var i = 0; i < missing.length; i++) {
									if(i > 0) error_message += ', ';
									error_message += missing[i];
								}

								alert(error_message);

							} else {
								return original_send.apply(this, arguments);
							}

						};

				// Show media modal
				if ( typeof wp !== 'undefined' && wp.media && wp.media.editor ) {
					wp.media.editor.open( 'content' );
				}

			});

		});

		</script>
<?php
	}

	/**
	 * Add custom fields to attachments.
	 *
	 * Based on https://gist.github.com/kosinix/5493051
	 */
	function attachment_fields() {
		add_filter( 'attachment_fields_to_edit', array(&$this, 'new_attachment_fields'), 9, 2 );
		add_filter( 'attachment_fields_to_save', array(&$this, 'update_attachment_meta'), 4, 2);
		add_action( 'wp_ajax_save-attachment-compat', array(&$this, 'media_extra_fields'), 0, 1 );
		add_filter( 'wp_prepare_attachment_for_js', array(&$this, 'media_fields_attachment_js'), 10, 3);
	}

	function media_fields_attachment_js( $response, $attachment, $meta ) {

		$photographer = get_post_meta($attachment->ID, 'media_photographer', true);

		$response['photographer'] = $photographer;
		return $response;
	}

	/**
	 * Add custom fields
	 */
	function new_attachment_fields( $form_fields, $post ) {

		if ( !( strpos( $post->post_mime_type, 'image' ) !== false ) ) {
			return $form_fields;
		}

		$meta = get_post_meta($post->ID, 'media_photographer', true);
		$form_fields['media_photographer'] = array(
			'label' => 'Fotograf <i style="color: red">*</i>',
			'input' => 'text',
			'value' => $meta,
			'show_in_edit' => true,
		);

		return $form_fields;
	}

	/**
	 * Update custom field on save
	 */
	function update_attachment_meta($post, $attachment){
		update_post_meta($post['ID'], 'media_photographer', $attachment['media_photographer']);
		return $post;
	}

	/**
	 * Update custom field with ajax
	 */
	function media_extra_fields() {
		$post_id = $_POST['id'];
		$meta = $_POST['attachments'][$post_id]['media_photographer'];
		update_post_meta($post_id , 'media_photographer', $meta);
		clean_post_cache($post_id);
	}

}
