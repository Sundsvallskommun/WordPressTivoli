</main>

<?php
if(!is_front_page() && !is_search()) {
	do_action('sk_page_widgets');
}
?>

<footer class="site-footer">

	<div class="container-fluid">

		<div class="row">

			<div class="site-footer__block site-footer__block--contact">

				<div class="site-logo">
					<?php the_icon('logo', array(
						'height' => 110,
						'width' => 276
					)); ?>
				</div>

				<h2 class="sr-only">Kontaktinformation</h2>

				<div class="contact-item contact-item--address">
					<span class="footer-icon">
						<?php the_icon('home', array('alt' => 'Adress')); ?>
					</span>
					<p>
						Sundsvalls Kommun <br>
						Norrmalmsgatan 4, 851 85 Sundsvall <br>
						<a href="">Kommunhuset - öppettider och karta</a> <br>
						Organisationsnummer: 212000-2411
					</p>
				</div>

				<div class="contact-item contact-item--phone">
					<span class="footer-icon">
						<?php the_icon('telephone', array('alt' => 'Telefon')); ?>
					</span>
					<p>
						<a href="tel:060191000">060-19 10 00</a>
					</p>
				</div>

				<div class="contact-item contact-item--email">
					<span class="footer-icon">
						<?php the_icon('message', array('alt' => 'E-post')); ?>
					</span>
					<p>
						<a href="mailto:sundsvalls.kommun@sundsvall.se">sundsvalls.kommun@sundsvall.se</a>
					</p>
				</div>

				<div class="contact-item contact-item--political-contact">
					<span class="footer-icon">
						<?php the_icon('kommun'); ?>
					</span>
					<p>
						<a href="mailto:sundsvalls.kommun@sundsvall.se">Kontakta politiker</a> <br>
						<a href="mailto:sundsvalls.kommun@sundsvall.se">Förtroendemannaregister</a>
					</p>
				</div>

				<div class="contact-item contact-item--error-report">
					<span class="footer-icon">
						<?php the_icon('exclamation-sign', array('alt' => 'Felanmälan')); ?>
					</span>
					<p>
						<a href="mailto:sundsvalls.kommun@sundsvall.se">Felanmälan</a>
					</p>
				</div>

			</div>

			<div class="site-footer__block site-footer__block--about">

				<h2>Om sundsvall.se</h2>

				<p>Sundsvall.se är Sundsvalls kommuns offentliga webbplats.</p>

				<nav>
					<ul class="list-unstyled">
						<li><a href="#">Webbplatsöversikt</a></li>
						<li><a href="#">Om webbplatsen</a></li>
						<li><a href="#">Cookies (kakor)</a></li>
						<li><a href="#">Tyck till om webbplatsen</a></li>
					</ul>
				</nav>

				<h2>Om Sundsvall</h2>

				<nav>
					<ul class="list-unstyled">
						<li><a href="#">Kartor</a></li>
						<li><a href="#">Youtube</a></li>
						<li><a href="#">Webbkameror</a></li>
						<li><a href="#">Press- och informationsmaterial</a></li>
						<li><a href="#">Turistbyrån, visit sundsvall</a></li>
					</ul>
				</nav>

			</div>

			<div class="site-footer__block site-footer__block--news">

				<h2>Senaste nytt från oss</h2>

				<h3>Följ oss i sociala medier</h3>
				<nav>
					<ul class="list-unstyled">
						<li> <a href="#"><?php the_icon('facebook'); ?> Facebook, </a> </li>
						<li> <a href="#"><?php the_icon('twitter'); ?> Twitter, </a> </li>
						<li> <a href="#"><?php the_icon('linkedin'); ?> LinkedIn</a> </li>
					</ul>
				</nav>

				<h3>För de senaste nyheterna</h3>

				<nav>
					<ul class="list-unstyled">
						<li><a href="#">Nyheter</a></li>
						<li><a href="#">Pressmeddelanden</a></li>
						<li><a href="#">Rss-flöden</a></li>
						<li><a href="#">Möten, protokoll och ärendelistor</a></li>
					</ul>
				</nav>

			</div>

		</div>

	</div>

</footer>

<?php get_template_part('partials/feedback-modal'); ?>

</div> <?php // .contentwrapper-inner ?>
</div> <?php // .contentwrapper-outer ?>

<?php wp_footer(); ?>

</body>
</html>
