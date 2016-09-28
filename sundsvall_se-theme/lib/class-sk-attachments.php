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
		// add_filter( 'img_caption_shortcode', array(&$this, 'photographer_caption'), 100, 3 );

		add_filter( 'the_content', array( $this, 'add_caption_shortcode' ) );
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
		add_action('admin_head', array( &$this, 'alt_text_asterisk' ));
		add_filter( 'attachment_fields_to_edit', array(&$this, 'new_attachment_fields'), 9, 2 );
		add_filter( 'attachment_fields_to_save', array(&$this, 'update_attachment_meta'), 4, 2);
		add_action( 'wp_ajax_save-attachment-compat', array(&$this, 'media_extra_fields'), 0, 1 );
		add_filter( 'wp_prepare_attachment_for_js', array(&$this, 'media_fields_attachment_js'), 10, 3);
	}

	function alt_text_asterisk() {
		echo "<style>label.setting[data-setting=alt] .name:after {
			color: red;
			content: ' *';
		}</style>";
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


	/**
	 * Add title to photos in content with photographer credit
	 */
	function photographer_caption( $output, $attr, $content ) {

		$attr = shortcode_atts( array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
		), $attr );


		// Find img tag and get photograper meta field.
		$document = new DOMDocument();
		libxml_use_internal_errors(true);
		$document->loadHTML(utf8_decode($content));

		$imgs = $document->getElementsByTagName('img');
		$src = $imgs->item(0)->getAttribute('src');
		$postid = get_attachment_id($src);

		$photographer = get_post_meta( $postid, 'media_photographer', true );

		// Add photographer as title attribute.
		if( isset( $photographer ) ) {
			$imgs->item(0)->setAttribute('title', "Foto: $photographer");
		}

		$content = $document->saveHTML();

		// Create caption
		if ( 1 > (int) $attr['width'] || empty( $attr['caption'] ) ) {
			return '';
		}

		if ( $attr['id'] ) {
			$attr['id'] = 'id="' . esc_attr( $attr['id'] ) . '" ';
		}

		$new_content = '<div ' . $attr['id']
			. 'class="wp-caption ' . esc_attr( $attr['align'] ) . '" '
			. 'style="max-width: ' . ( 10 + (int) $attr['width'] ) . 'px;">';
		$new_content .= do_shortcode( $content );
		$new_content .= '<p class="wp-caption-text">' . $attr['caption'];
		// Add photograper to caption
		if( isset( $photographer ) ) {
			$new_content .= " Foto: $photographer";
		}
		$new_content .= '</p>';
		$new_content .= '</div>';

		return $new_content;

	}

	/**
	 * Adds the [caption] shortcode to all images.
	 * @param  string $content
	 * @return string
	 */
	public function add_caption_shortcode( $content ) {
		// Set up DOMDocument which will help with
		// manipulating the content.
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML( utf8_decode( $content ) );

		// Get all images.
		foreach ( $doc->getElementsByTagName( 'img' ) as $img ) {
			// Get the post id from the class attribute.
			if ( preg_match( '/.*-(?P<id>\d*)/', $img->getAttribute( 'class' ), $match ) ) {
				$id = $match[ 'id' ];
				$src = $img->getAttribute( 'src' );

				// Get the photographer.
				$photographer = get_post_meta( $id, 'media_photographer', true );

				// Add a caption if image doesn't have one.
				if ( $img->previousSibling->nodeName !== '#text' && ! strpos($img->previousSibling->data, '[caption') ) {
					// Setup some variables.
					preg_match( '/.*-(?P<id>\d*)/', $img->getAttribute( 'class' ), $matches );
					$width = $img->getAttribute( 'width' );

					$caption_start = $doc->createTextNode( "[caption id=\"{$id}\" align=\"alignnone\" width=\"{$width}\"]" );
					$caption_end = $doc->createTextNode( "Foto: {$photographer}[/caption]" );

					// Append.
					$img->parentNode->insertBefore( $caption_start, $img );
					$img->parentNode->appendChild( $caption_end );
				}

				// Append photographer name to caption if image already has one.
				else {
					/**
					 * Get the previous value by using substring.
					 *
					 * We'll extract everything from nodeValue except for the last 10 characters
					 * which will always be "[/caption]".
					 *
					 * NOTE: Probably should use regex?
					 */
					$previous_value = substr( $img->nextSibling->nodeValue, 0, ( strlen( $img->nextSibling->nodeValue ) - 11 ) );

					// Set the new value.
					$img->nextSibling->nodeValue = sprintf( '%s Foto: %s[/caption]', $previous_value, $photographer );
				}
			}
		}

		// Return as html.
		return $doc->saveHTML();
	}

}
