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
	 * Don't allow insertion of images without alt.
	 */
	function validate_media_javascript() {
?>
		<script>

		jQuery(function($){

			$('.wp-media-buttons .add_media').on( 'click', function( event ){

						var original_send = wp.media.editor.send.attachment;

						wp.media.editor.send.attachment = function( props, attachment ) {

							if('image' ==! attachment.type) {
								return original_send.apply(this, arguments);
							}

							if( !attachment.alt ) {
								alert('You need to specify an alt-text.');
							} else {
								return original_send.apply(this, arguments);
							}

						};

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
	}

	/**
	 * Add custom fields
	 */
	function new_attachment_fields( $fields, $post ) {
		$meta = get_post_meta($post->ID, 'media_photographer', true);
		$fields['media_photographer'] = array(
			'label' => 'Fotograf',
			'input' => 'text',
			'value' => $meta,
			'show_in_edit' => true,
		);
		return $fields;
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
