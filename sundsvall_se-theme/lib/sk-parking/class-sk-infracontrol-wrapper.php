<?php
/**
 * InfraControl wrapper
 *
 * Wraps functionality for reading and retrieving data from Easycruits API.
 *
 * @since  1.0.0
 */

class SK_Infracontrol_Wrapper {

	/**
	 * Singleton instance of class.
	 * @var SK_Easycruit_Wrapper|null
	 */
	private static $instance = null;

	/**
	 * URL to web service.
	 * @var string
	 */
	private static $WS_URL = 'https://parking.infracontrol.com/services/parkinginfo.asmx?wsdl';

	/**
	 * Last retrieved parking lots.
	 * @var array|null
	 */
	private $parking_lots = null;

	/**
	 * Bleh.
	 */
	private function __construct() {}

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
	 * Returns parking lots if we have already queried for them and
	 * saved them or we retrieve them from API.
	 * @return array|boolean
	 */
	public function get_parking_lots() {
		if ( $this->parking_lots === null ) {
			$this->parking_lots = $this->get_parking_lots_from_api();
		}

		// Then return them.
		return $this->parking_lots;
	}

	/**
	 * Retrieves all parking lots from API.
	 * @return array|boolean
	 */
	private function get_parking_lots_from_api() {
		// Initiate SoapClient.
		$client = new SoapClient( self::$WS_URL );

		// Get all parking lots from API.
		// Format is XML.
		try {
			$params = array(
			'userName'	=> 'sundsvall\ws.sundsvall',
			'password'	=> 'f9pFqeWmwDBS'
			);
			$result = $client->ListSites($params);

			// Convert to a more user friendly object 
			// and then return it.
			return $result->ListSitesResult->Site;
		}

		catch ( Exception $ex ) {
			// Save error in class.
			$this->last_error = $ex->faultstring;

			// Fail silently and return false.
			return false;
		}
	}

}