<?php
/**
 *
 */

class SK_Expiration {

	const META_NAME = 'sk_expiry_date';

	public function __construct() {

		add_action('admin_enqueue_scripts', array( &$this, 'datepicker_scripts' ) );
		add_action('add_meta_boxes', array( &$this, 'add_expiration_metabox' ) );
		add_action('save_post', array( &$this, 'save_expiration_meta' ) );
	}

	public function datepicker_scripts() {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}

	public function add_expiration_metabox() {
		add_meta_box(
			'sk_expiration_metabox',
			'Utgångsdatum',
			array(&$this, 'sk_expiration_metabox_callback'),
			'post',
			'side',
			'high'
		);
	}

	public function sk_expiration_metabox_callback($post) { 

		wp_nonce_field( 'sk_expiry_date_nonce', 'sk_nonce' ); ?>

		<form action="" method="post">

			<?php $expiry_date = get_post_meta( $post->ID, self::META_NAME, true ); ?>

			<label for="sk_expiry_date"><?php _e( 'Avpubliceringsdatum', 'sundsvall_se' ); ?></label>

			<input type="text" class="expiry-date" id="sk_expiry_date" name="sk_expiry_date" value="<?php echo esc_attr( $expiry_date ); ?>" / >
				<script type="text/javascript">
						jQuery(document).ready(function() {
								jQuery('.expiry-date').datepicker({
										dateFormat : 'yy-mm-dd',
										maxDate : '+3M',
										minDate : '+1'
								});
						});
				</script>
		</form>

	<?php }

	public function save_expiration_meta( $post_id ) {

		if( !isset( $_POST['sk_nonce'] ) ||
			!wp_verify_nonce( $_POST['sk_nonce'],
				'sk_expiry_date_nonce'
			) ) 
		return;

		if( !current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		if ( isset( $_POST['sk_expiry_date'] ) ) {
			$expiry_date = ( $_POST['sk_expiry_date'] );

			update_post_meta( $post_id, self::META_NAME, $expiry_date );
		}

	}

}
