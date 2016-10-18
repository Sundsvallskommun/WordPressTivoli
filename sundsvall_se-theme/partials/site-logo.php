<?php $has_logo = function_exists( 'the_custom_logo' ) && has_custom_logo(); ?>

<div class="site-logo">

<?php 
if ( $has_logo ) {
			the_custom_logo();
		} else {
			echo '<a href="'.get_bloginfo('url').'">';
			printf( '<span class="site-title">%s</span>', get_bloginfo( 'name' ) );
			echo '</a>';
		}
?>
</div>
