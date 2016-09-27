<div class="site-logo">

<?php 
if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
		} else {
			echo '<a href="'.get_bloginfo('url').'">';
			the_icon('logo', array(
				'height' => 110,
				'width' => 276,
				'alt' => sprintf(__('%s logotyp, länk till startsidan.', 'sundsvall_se'), get_bloginfo('title'))
			));
			echo '</a>';
		}
?>
</div>
