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
	 * Filters page contacts for single vacancy pages.
	 * @return array|boolean
	 */
	public function filter_page_contacts( $contacts ) {
		// We're only interested in changing anything if
		// we're showing a single vacancy because it's then
		// that we want to add the applications contact info.
		if ( $this->is_single() ) {

			// Also make sure that we have a valid single vacancy.
			$vacancy = $this->api_w->get_single( $this->get_vacancy_id() );
			if ( $vacancy ) {
				// Reset array.
				$contacts = array();

				// Loop through all contact_persons for this vacancy
				// and add them to the $contacts array.
				foreach ( $vacancy->contact_persons as $contact ) {
					// Inner loop for phone numbers since function is expecting
					// comma separated string.
					$phone = '';
					foreach ( $contact->phonenumbers as $type => $number ) {
						$phone .= ',' . $number;
					}
					$phone = ltrim( $phone, ',' );

					// Add to array.
					$contacts[] = array(
						'name'		=> $contact->name,
						'phone'		=> $phone
					);
				}

				// Add thumbnail argument.
				//$contacts['show_thumb'] = true;
			}


		}

		// Return original.
		return $contacts;
	}

	/**
	 * @return string HTML output for vacancies
	 */
	public function sc_vacancy_func() {

		// $html will be returned at end of func.
		$html = '';

		// First, check if we're showing single...
		if ( $this->is_single() ) {
			$vacancy = $this->api_w->get_single( $this->get_vacancy_id() );
			if ( $vacancy ) {
				$icon = get_icon( 'arrow-right' );
				$html .= <<<XYZ
				<div class="vacancy">
					<span class="last-application-date">Sista ansökningsdagen: {$vacancy->date_end}</span>

					<div class="apply">
						<a class="btn btn-purple btn-action" href="{$vacancy->apply_link}">{$icon} Sök jobbet</a>
					</div>

					<p>{$vacancy->{'sub-title'}}</p>
					{$vacancy->description}
XYZ;

				// Close div.vacancy
				$html .= '</div>';
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
			<div class="input-group" style="display: none;">
				Sortera efter

				<select class="c-select order">
					<option class="order-option" value="dateend"><a href="{$orderby_dateend_link}">Ansökningsdatum</a></option>
					<option class="order-option" value="title"><a href="{$orderby_title_link}">Rubrik</a></option>
				</select>
			</div>
XYZ;

			// Add categories radio buttons.
			$html .= '<div class="categories m-y-1" data-toggle="buttons" style="display: none">';
				$html .= <<<XYZ
				<label class="btn btn-secondary active">
					<input type="radio" name="category" id="all" value="all" class="category" checked>
					Visa alla
				</label>
XYZ;
				foreach ( $this->api_w->get_vacancies_categories() as $category => $count ) {
					$id = sanitize_title( $category );
					$html .= <<<XYZ
					<label class="btn btn-secondary">
						<input type="radio" name="category" id="{$id}" value="{$id}" class="category">
						{$category}
					</label>
XYZ;
				}

			// Close div.input-group
			$html .= '</div>';

			// Get all vacancies.
			$vacancies = $this->api_w->get_all_vacancies( $this->get_orderby() );
			if ( $vacancies ) {
				$html .= '<div class="vacancies list-group">';
				foreach ( $vacancies as $vacancy ) {
					$url = add_query_arg( 'vacancyID', $vacancy->id );

					// Save timestamps as UNIX aswell.
					$date_start_unix = strtotime( $vacancy->date_start );
					$date_end_unix = strtotime( $vacancy->date_end );

					$html .= <<<XYZ
					<a href="{$url}" class="vacancy list-group-item" data-category="{$vacancy->sanitized_cat}" data-dateend="{$date_end_unix}">
						<h3 class="list-group-item-heading"><strong>{$vacancy->title}</strong></h3>
						<p class="list-group-item-text">{$vacancy->description}</p>
						<p class="last-application-date list-group-item-text"> <small>Sista ansökningsdagen: {$vacancy->date_end}</small></p>
					</a>
XYZ;
				}

				$html .= '</div>';
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
		$vacancy_id = get_query_var( 'vacancyID' );
		return !empty( $vacancy_id );
	}

	/**
	 * Retrieves the vacancy id user has requested to see from query string.
	 *
	 * Returns false if none is found.
	 * @return integer|boolean
	 */
	private function get_vacancy_id() {
		$vacancy_id = get_query_var( 'vacancyID' );

		if ( !empty( $vacancy_id ) && $vacancy_id > 0 ) {
			return (int) $vacancy_id;
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
add_filter( 'sk_page_contacts', array( SK_Vacancy::get_instance(), 'filter_page_contacts' ), 10, 1 );

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