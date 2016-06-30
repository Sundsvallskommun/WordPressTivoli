<?php
/**
 * Parking
 *
 * By adding a shortcode to a page we can show a list of available
 * parking spaces and lots.
 *
 * @since  1.0.0
 */

require_once dirname( __FILE__ ) . '/class-sk-infracontrol-wrapper.php';

class SK_Parking {

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
		$this->api_w = SK_Infracontrol_Wrapper::get_instance();
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
	 * @return string HTML output for vacancies
	 */
	public function sc_parking_func() {
		// We'll return $html which will contain result.
		$html = '';

		// Get parking lots from wrapper.
		$parking_lots = $this->api_w->get_parking_lots();
		if ( $parking_lots ) {
			$html .= <<<XYZ
		<table class="table table-striped">
			<thead class="thead-inverse">
				<tr>
					<th>Namn</th>
					<th>Lediga platser</th>
					<th>Öppet</th>
				<tr>
			</thead>
			<tbody>
XYZ;

			// Loop through sites.
			foreach ( $parking_lots as $parking_lot ) {
				$vacancies = $parking_lot->MaxOccupancy - $parking_lot->Occupancy;
				$open = ( $parking_lot->Active ) ? 'Ja' : 'Nej';
				$html .= <<<XYZ
				<tr>
					<td>{$parking_lot->Name}</td>
					<td>{$vacancies}</td>
					<td>{$open}</td>
				</tr>
XYZ;
			}

			// Close table.
			$html .= <<<XYZ
			</tbody>
		</table>
XYZ;
		}

		else {
			$html .= '<p>Ett fel uppstod vid hätmnning av parkeringsplatser. Försök igen senare!<p>';
		}

		return $html;
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


// Register our shortcode.
// Alternative 'ledigajobb' for swedish.
add_shortcode( 'sk-parking', array( SK_Parking::get_instance(), 'sc_parking_func' ) );
add_shortcode( 'parkeringsplatser', array( SK_Parking::get_instance(), 'sc_parking_func' ) );

// Register JS used in vacancies list.
// wp_register_script( 'sk-vacancies-list', get_template_directory_uri() . '/assets/js/sk_vacancies_list.min.js', array( 'jquery' ), '1.0.0', true );