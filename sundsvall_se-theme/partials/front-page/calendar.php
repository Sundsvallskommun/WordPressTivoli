<?php

$page_id = is_front_page() ? get_option('page_on_front') : $post->ID;

// Only show calendar if it has been activated.
if ( !get_field( 'event_calendar_active', $page_id ) ) return false;

?>

<div class="container-fluid container-fluid--full bg-primary">

	<div class="row">

		<div class="col-md-12">

<section class="front-page-section front-page-section__calendar">

<script type="text/javascript">
	//<![CDATA[
	(function () {
	var s = document.createElement('script'); s.type = 'text/javascript';s.async = true;
	s.src ='http://webapps.citybreak.com/597883702/sv/sv-se/widget/GenerateEventCalendarWidget?eventbaseurl=http%3A%2F%2Fguide.visitsundsvall.se%2Fsv%2Flink%2Fproduct%2F&eventlistingurl=http%3A%2F%2Fguide.visitsundsvall.se%2Fsv%2Fevenemang%2F&online3=true&defaultcss=true&images=&css=http://resources.citybreak.com/citybreakweb/sundsvall/widget/css/style_with_time.css&notruncate=true&blanktarget=true&geoId=3497&showDateTime=true'
	var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
	})();
	//]]>
</script>

<div class="calendar-image hidden-sm-down" style="background-image: url(<?php the_field( 'event_calendar_left_image', $page_id ); ?>)"> </div>

<div class="container-fluid">

<div class="row">

	<div class="clearfix"></div>

	<div class="col-md-7 calendar-left front-page-section__calendar__left hidden-sm-down">
		<h2 class=""><?php the_field( 'event_calendar_left_heading', $page_id ); ?></h2>
		<div class="content"><?php the_field(  'event_calendar_left_description', $page_id ); ?></div>
	</div>

	<div class="col-md-5">
		<h2><?php echo ucfirst( date_i18n('l') ); ?><strong><?php echo date_i18n(' j F'); ?></strong></h2>
		<h3>Detta händer i Sundsvall i dag</h3>
		<div id="citybreak_event_calendar_widget"></div>
	</div>
</div>
</div>

</section>

		</div>

	</div>

</div>
