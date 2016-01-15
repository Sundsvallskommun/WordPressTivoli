<?php
get_header();
?>

<?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 

		the_permalink();
		echo ' ';
		the_title();
	} // end while
} // end if
?>

<?php
get_footer();
?>
