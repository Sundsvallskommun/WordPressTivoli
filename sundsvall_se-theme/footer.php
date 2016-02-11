</main>

<footer class="site-footer" role="contentinfo">

	<div class="container-fluid">

		<div class="row">

			<div class="col-md-4 site-footer__contact-block">

				<div class="site-logo">
					<img src="<?php bloginfo('template_directory')?>/assets/images/logo-white.svg" alt="<?php printf(__('%s logotyp, länk till startsidan.', 'sundsvall_se'), bloginfo('title')); ?>">
				</div>

				<h2 class="sr-only">Kontaktinformation</h2>

				<div class="contact-item contact-item--address">
					<span class="footer-icon">
						<?php the_icon('home', array('alt' => 'Adress')); ?>
					</span>
					<p>
						Sundsvalls Kommun <br>
						Norrmalmsgatan 4, 851 85 Sundsvall
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

				<div class="contact-item contact-item--phone">
					<span class="footer-icon">
						<?php the_icon('telephone', array('alt' => 'Telefon')); ?>
					</span>
					<p>
						<a href="">060-19 10 00</a>
					</p>
				</div>

				<p class="extra-contacts">Kontaktuppgifter till alla anställda hittar du här</p>

			</div>

			<div class="col-md-4 site-footer__about-block">

				<h2>Om sundsvall.se</h2>

				<p>Sundsvall.se är Sundsvalls kommuns offentliga webbplats.</p>

				<nav>
					<ul class="list-unstyled">
						<li><a href="#">Om webbplatsen</a></li>
						<li><a href="#">Om cookies</a></li>
						<li><a href="#">Press- och informationsmaterial</a></li>
						<li><a href="#">Tyck till om webbplatsen</a></li>
						<li><a href="#">Våra RSS-flöden</a></li>
					</ul>
				</nav>

			</div>

			<div class="col-md-4 site-footer__social-block">

				<h3>Har du frågor om webbplatsen?</h3>
				<p><a href="#">erik.webbansvarig@sundsvall.se</a></p>

				<h3>För de senaste nyheterna</h3>
				<p>Följ Sundsvalls kommun i våra sociala kanaler</p>

			</div>

		</div>

	</div>

</footer>

<?php wp_footer(); ?>

</body>
</html>
