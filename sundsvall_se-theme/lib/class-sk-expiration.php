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

		add_action( 'sk_unpublish_expired', array( &$this, 'unpublish_expired_content' ) );
		add_action('init', array( &$this, 'unpublish_expired_content_cron' ) );
	}

	/**
	 * Enqueue scripts and styles for jquery ui datepicker
	 */
	public function datepicker_scripts() {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}

	/**
	 * Add metabox to posts where we will add datepicker input
	 */
	public function add_expiration_metabox() {
		add_meta_box(
			'sk_expiration_metabox',
			'Utgångsdatum',
			array(&$this, 'sk_expiration_metabox_callback'),
			array('post', 'page'),
			'side',
			'high'
		);
	}

	/**
	 * Add datepicker form with custom validation.
	 */
	public function sk_expiration_metabox_callback($post) { 

		wp_nonce_field( 'sk_expiry_date_nonce', 'sk_nonce' ); ?>

		<form action="" method="post">

			<?php $expiry_date = get_post_meta( $post->ID, self::META_NAME, true ); ?>

		<p>
			<label for="sk_expiry_date">
				<?php _e( 'Avpubliceringsdatum', 'sundsvall_se' ); ?>
				<?php if ( $post->post_type === 'post' ) : ?><span style="color: red">*</span><?php endif; ?>
			</label>
		</p>

		<p> <input type="text" class="expiry-date" id="sk_expiry_date" name="sk_expiry_date" value="<?php echo esc_attr( $expiry_date ); ?>" / > </p>

		<p><small>När avpubliceringsdatumet passerat blir nyhetens status satt till utkast.</small></p>

				<script type="text/javascript">
					jQuery(document).ready(function() {
							jQuery('.expiry-date').datepicker({
									dateFormat : 'yy-mm-dd',
									maxDate : '+3M',
									minDate : '+1'
							});
					});

					jQuery(document).ready(function($){
						$('#post').submit(function(){

							var $dateInput = $('.expiry-date');
							var dateValue = $dateInput.val();


							<?php if ( $post->post_type === 'post' ) : ?>
								if( dateValue == '' ) {
									alert('Du måste ange ett avpubliceringsdatum.');
									return invalidDate();
								}

								var date = new Date(dateValue);

								var m3 = new Date();
								m3.setMonth( m3.getMonth() + 3 );

								if( date > m3 ) {
									alert('Du måste ange ett avpubliceringsdatum som är max 3 månader från idag.');

									return invalidDate();
								}
							<?php endif; ?>
						});

						<?php if ( $post->post_type === 'post' ) : ?>
							function invalidDate() {
								$('.expiry-date').css('border-color', 'red');

								return false;

							}
						<?php endif; ?>
					});
				</script>
		</form>

	<?php }

	/**
	 * Save expiration date on post save
	 */
	public function save_expiration_meta( $post_id ) {

		if( !isset( $_POST['sk_nonce'] ) ||
			!wp_verify_nonce( $_POST['sk_nonce'],
				'sk_expiry_date_nonce'
			) ) 
		return;

		if( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['sk_expiry_date'] ) ) {
			$expiry_date = ( $_POST['sk_expiry_date'] );

			update_post_meta( $post_id, self::META_NAME, $expiry_date );
		}

	}

	public function unpublish_expired_content_cron() {

		if (! wp_next_scheduled ( 'sk_unpublish_expired' )) {
			wp_schedule_event(time(), 'hourly', 'sk_unpublish_expired');
		}

	}

	public function unpublish_expired_content() {

		$today = date('Y-m-d');

		$query_args = array(
				'post_status' => 'publish',
				'meta_key' => self::META_NAME,
				'meta_value' => $today,
				'meta_compare' => '<=',
				'meta_type' => 'DATE'
		);

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				wp_update_post( array(
					'ID'          => get_the_id(),
					'post_status' => 'draft'
				));

			}
		}

	}

}
