<?php


class SK_News {

	/**
	 * SK_News constructor.
	 */
	function __construct() {
		add_action( 'after_setup_theme', function () {
			$this->init();
		} );

	}

	/**
	 * Run after theme functions.php
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 */
	public function init() {
		add_shortcode( 'tivoli-nyheter', array( $this, 'output' ) );
	}


	/**
	 * HTML output for the latest news.
	 *
	 * @author Daniel Pihlström <daniel.pihlstrom@cybercom.com>
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function output( $atts, $content ) {

		ob_start();

		$a = shortcode_atts( array(
			'title' => false,
			'text'  => false
		), $atts );

		?>
		<div class="block">
		<div class="widget widget-latest-news">
			<div class="mobile-news" id="news">
				<?php get_template_part( 'partials/latest-news' ); ?>
			</div>
			<div class="clearfix"></div>
		</div>
		</div>
		<?php
		return ob_get_clean();

	}


}