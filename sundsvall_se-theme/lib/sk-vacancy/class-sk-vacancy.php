<?php
/**
 * Vacanies
 *
 * By adding a shortcode to a page we can show a list of available
 * vacancies with every vacancy having a link to a single view
 * that shows some more information about the job.
 *
 * @since  1.0.0
 */

require_once dirname( __FILE__ ) . '/class-sk-easycruit-wrapper.php';

class SK_Vacancy {

	/**
	 * Singleton instance of class.
	 * @var SK_Vacancy|null
	 */
	private static $instance = null;

	/**
	 * Easycruit API wrapper.
	 * @var SK_Easycruit_Wrapper|null
	 */
	private $api_w = null;

	/**
	 * Adds our shortcode among some other stuff.
	 */
	private function __construct() {
		// Instanciate API wrapper.
		$this->api_w = SK_Easycruit_Wrapper::get_instance();
	}

	/**
	 * Returns singleton instance.
	 * @return SK_Easycruit_Wrapper
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param  string
	 * @param  integer|null
	 * @return string
	 */
	public function filter_the_title( $title, $id = null ) {
		global $post;

		// Let's try our best to only alter the main post title.
		if ( is_main_query() && in_the_loop() && $this->is_single() && $post->post_title === $title ) {
			$vacancy = $this->api_w->get_single( $this->get_vacancy_id() );
			if ( $vacancy ) {
				return $vacancy->title;
			}

			else {
				return __( 'Inget jobb med hittades', 'sundsvall_se' );
			}
		}

		return $title;
	}

	/**
	 * @return string HTML output for vacancies
	 */
	public function sc_vacancy_func() {

		// $html will be returned at end of func.
		$html = '';

		// Add a order dropdown.
		$orderby_title_link = add_query_arg( 'orderby', 'title' );
		$orderby_dateend_link = add_query_arg( 'orderby', 'dateend' );
		$html .= <<<XYZ
		<div class="btn-group">
			<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Sortera efter
			</button>

			<div class="dropdown-menu">
				<a class="dropdown-item order-option" data-orderby="title" href="{$orderby_title_link}">Rubrik</a>
				<a class="dropdown-item order-option" data-orderby="date_end" href="{$orderby_dateend_link}">Sista datum</a>
			</div>
		</div>
XYZ;

		// First, check if we're showing single...
		if ( $this->is_single() ) {
			$vacancy = $this->api_w->get_single( $this->get_vacancy_id() );
			if ( $vacancy ) {
				$html .= <<<XYZ
				<div class="vacancy">
					<p>{$vacancy->description}</h3>
					<span class="last-application-date">Sista ansökningsdagen: {$vacancy->date_end}</span>
				</div>
XYZ;
			}
		}

		// Otherwise we'll show the entire list.
		else {
			// Enqueue our JS.
			// wp_enqueue_script( 'sk-vacancies-list' );

			// Add a order dropdown.
			$orderby_title_link = add_query_arg( 'orderby', 'title' );
			$orderby_dateend_link = add_query_arg( 'orderby', 'dateend' );
			$html .= <<<XYZ
			<div class="btn-group">
				<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Sortera efter
				</button>

				<div class="dropdown-menu">
					<a class="dropdown-item order-option" data-orderby="title" href="{$orderby_title_link}">Rubrik</a>
					<a class="dropdown-item order-option" data-orderby="date_end" href="{$orderby_dateend_link}">Ansökningsdatum</a>
				</div>
			</div>
XYZ;

			// Get all vacancies.
			$vacancies = $this->api_w->get_all_vacancies( $this->get_orderby() );
			if ( $vacancies ) {
				$html .= '<ul class="vacancies list-group">';
				foreach ( $vacancies as $vacancy ) {
					$url = add_query_arg( 'vacancyID', $vacancy->id );

					// Save timestamps as UNIX aswell.
					$date_start_unix = strtotime( $vacancy->date_start );
					$date_end_unix = strtotime( $vacancy->date_end );

					$html .= <<<XYZ
					<li class="vacancy list-group-item" data-dateend="{$date_end_unix}"">
						<a href="{$url}"">
							<h4 class="list-group-item-heading">{$vacancy->title}</h4>
							<p class="list-group-item-text">{$vacancy->description}</p>
							<p class="last-application-date list-group-item-text">Sista ansökningsdagen: {$vacancy->date_end}</p>
						</a>
					</li>
XYZ;
				}

				$html .= '</ul>';
			}
		}

		// Return html.
		return $html;

	}

	/**
	 * Checks if user has requested to see a specific vacancy.
	 * @return boolean
	 */
	private function is_single() {
		return !empty( get_query_var( 'vacancyID' ) );
	}

	/**
	 * Retrieves the vacancy id user has requested to see from query string.
	 *
	 * Returns false if none is found.
	 * @return integer|boolean
	 */
	private function get_vacancy_id() {
		if ( !empty( get_query_var( 'vacancyID' ) ) && get_query_var( 'vacancyID' ) > 0 ) {
			return (int) get_query_var( 'vacancyID' );
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 */
	private function get_orderby() {
		return ( get_query_var( 'orderby' ) !== null ) ?
			get_query_var( 'orderby' ) : 'id';
	}

}

/**
 * *=========================================*
 *              MISCELLANEOUS
 * *=========================================*
 */

/**
 * Register our actions, filters, shortcodes and scripts.
 */


// Add a filter to the_title so we can change it to the
// vacancy's title if we're showing a single.
add_filter( 'the_title', array( SK_Vacancy::get_instance(), 'filter_the_title' ), 10, 2 );

// Register our shortcode.
// Alternative 'ledigajobb' for swedish.
add_shortcode( 'sk-vacancy', array( SK_Vacancy::get_instance(), 'sc_vacancy_func' ) );
add_shortcode( 'ledigajobb', array( SK_Vacancy::get_instance(), 'sc_vacancy_func' ) );

/**
 * Register our query var with WP so it's easier to retrieve it later.
 */
add_filter( 'query_vars', function($vars) {
	$vars[] = 'vacancyID';
	return $vars;
} );

// Register JS used in vacancies list.
// wp_register_script( 'sk-vacancies-list', get_template_directory_uri() . '/assets/js/sk_vacancies_list.min.js', array( 'jquery' ), '1.0.0', true );