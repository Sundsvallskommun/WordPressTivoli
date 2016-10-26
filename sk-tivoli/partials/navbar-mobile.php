<nav class="mobile-header not-fixed" <?php theme_color('border-top-color', 1); ?>>
	<?php get_template_part('partials/site-logo'); ?>
</nav>
<a class="btn-mobile-fixed btn-mobile-fixed-bottom-right" data-toggle="offcanvas-bottom" aria-hidden="true" href="#mainNavigation">
	<span class="iconwrapper icon-inactive"><?php the_icon('menu'); ?></span>
	<span class="iconwrapper icon-active"><?php the_icon('close'); ?></span>
</a>

<a class="btn-mobile-fixed btn-mobile-fixed-bottom-left" data-toggle="search"
href="#searchContainer">
<span class="iconwrapper icon-inactive"><?php the_icon('search'); ?></span>
<span class="iconwrapper icon-active"><?php the_icon('close'); ?></span>
</a>

