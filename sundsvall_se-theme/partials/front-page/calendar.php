<section class="front-page-section front-page-section__calendar">

<script type="text/javascript">
	//<![CDATA[
	(function () {
	var s = document.createElement('script'); s.type = 'text/javascript';s.async = true;
	s.src ='http://webapps.citybreak.com/597883702/sv/sv-se/widget/GenerateEventCalendarWidget?eventbaseurl=http%3A%2F%2Fguide.visitsundsvall.se%2Fsv%2Flink%2Fproduct%2F&eventlistingurl=http%3A%2F%2Fguide.visitsundsvall.se%2Fsv%2Fevenemang%2F&online3=true&defaultcss=true&images=&css=http://resources.citybreak.com/citybreakweb/sundsvall/widget/css/style_with_time.css&notruncate=true&blanktarget=true&geoId=3497&arena=914533&arena=916347&arena=915750&arena=915751&arena=915760&arena=66991&arena=915672&arena=89713&arena=72258&arena=915779&arena=915784&arena=915800&arena=830322&showDateTime=true'
	var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
	})();
	//]]>
</script>


<?php $front_page_id = get_option('page_on_front'); ?>

<div class="calendar-image" style="background-image: url(<?php the_field( 'event_calendar_left_image', $front_page_id ); ?>)"> </div>

<div class="container-fluid">

<div class="row">

	<div class="col-md-7 calendar-left front-page-section__calendar__left">
		<h2 class=""><?php the_field( 'event_calendar_left_heading', $front_page_id ); ?></h2>
		<div class="content"><?php the_field(  'event_calendar_left_description', $front_page_id ); ?></div>
	</div>

	<div class="col-md-5">
		<h2><?php echo ucfirst( date_i18n('l') ); ?><strong><?php echo date_i18n(' j F'); ?></strong></h2>
		<h3>Detta händer i Sundsvall i dag</h3>
		<div id="citybreak_event_calendar_widget"></div>
	</div>
</div>
</div>

</section>

