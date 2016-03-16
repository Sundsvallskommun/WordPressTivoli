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
	}

	/**
	 * Add custom fields to attachments.
	 *
	 * Based on https://gist.github.com/kosinix/5493051
	 */
	function attachment_fields() {
		add_filter( 'attachment_fields_to_edit', 'new_attachment_fields', 9, 2 );
		add_filter( 'attachment_fields_to_save', 'update_attachment_meta', 4 );
		add_action( 'wp_ajax_save-attachment-compat', 'media_extra_fields', 0, 1 );
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
	function update_attachment_meta($attachment){
		global $post;
		update_post_meta($post->ID, 'media_photographer', $attachment['attachments'][$post->ID]['media_photographer']);
		return $attachment;
	}

	/**
	 * Update custom field with ajax
	 */
	function mytheme_media_xtra_fields() {
		$post_id = $_POST['id'];
		$meta = $_POST['attachments'][$post_id ]['media_photographer'];
		update_post_meta($post_id , 'media_photographer', $meta);
		clean_post_cache($post_id);
	}

}
