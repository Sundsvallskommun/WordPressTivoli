<?php sk_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="container-fluid">

<div class="single-post__row">

	<aside class="sk-sidebar single-post__sidebar">

		<a href="#post-content" class="focus-only"><?php _e('Hoppa över sidomeny', 'sk_tivoli'); ?></a>

		<?php do_action('sk_page_helpmenu'); ?>

	</aside>

	<div class="single-post__content" id="post-content">

		<?php do_action('sk_before_page_title'); ?>

		<h1 class="single-post__title"><?php the_title(); ?></h1>

		<?php do_action('sk_after_page_title'); ?>

		<?php do_action('sk_before_page_content'); ?>

		<?php the_content(); ?>

		<?php the_post_thumbnail( 'portrait', array( 'class' => 'alignleft' ) ); ?>

		<div style="display: inline-block;">
		<?php 

			$fields = array(
				'role' => array( 'Roll', get_field('role') ),
				'email' => array( 'E-post', get_email_links( get_field('email') ) ),
				'phone' => array( 'Telefon', get_phone_links( get_field('phone') ) ),
				'address' => array( 'Adress', get_field('address') ),
				'hours' => array( 'Öppettider', get_field('hours') ),
				'custom_fields' => array( '', get_field('contact_custom_fields') ),
				'description' => array( '', get_field('description') ),

			);

			// remove empty arrays
			$fields['custom_fields'] = array_filter( $fields['custom_fields'] );
			echo '<dl>';
			foreach ($fields as $key => $field) {

				if ( ! isset( $field[1] ) ) {
					continue;
				}

				if ( $key === 'custom_fields' && ! empty( $field[1] ) ) {

					foreach ( $field[1] as $custom ){
						$label = get_term_by('term_id', $custom['contact_custom_field_title'], 'contact_persons_labels' );
						printf( '<dt>%s</dt><dd>%s</dd>', $label->name, $custom['contact_custom_field_value']);
					}

				}else {
					if ( ! empty( $field[1] ) ) {
						printf( '<dt>%s</dt><dd>%s</dd>', $field[0], $field[1] );
					}
				}
			}
			echo '</dl>';

		?>
		</div>

		<div class="clearfix"></div>

		<?php do_action('sk_after_page_content'); ?>

	</div>

</div> <?php //.row ?>

</div> <?php //.container-fluid ?>

<?php endwhile; ?>

<?php get_footer(); ?>
