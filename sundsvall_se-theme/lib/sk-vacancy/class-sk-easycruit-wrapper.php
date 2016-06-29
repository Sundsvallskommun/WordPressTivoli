<?php
/**
 * Easycruit wrapper
 *
 * Wraps functionality for reading and retrieving data from Easycruits API.
 *
 * @since  1.0.0
 */

class SK_Easycruit_Wrapper {

	/**
	 * Singleton instance of class.
	 * @var SK_Easycruit_Wrapper|null
	 */
	private static $instance = null;

	/**
	 * URL to list of available vacancies
	 * @var string
	 */
	private static $LIST_URL = 'https://sundsvall.easycruit.com/export/xml/vacancy/list.xml';

	/**
	 * URL to single vacancy info
	 * @var string
	 */
	private static $SINGLE_URL = 'https://sundsvall.easycruit.com/export/xml/vacancy/%d.xml';

	/**
	 * Last retrieved single vacancy.
	 * @var StdClass|null
	 */
	private $vacancy_single = null;

	/**
	 * Last retrieved vacancy list.
	 * @var array|null
	 */
	private $vacancy_list = null;

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
	 * Returns vacancy by id.
	 * @param  integer
	 * @return StdClass
	 */
	public function get_single( $vacancy_id ) {
		if ( $this->vacancy_single === null || $this->vacancy_single->id !== $vacancy_id ) {
			$this->vacancy_single = $this->get_single_from_api( $vacancy_id );			
		}

		return $this->vacancy_single;
	}

	/**
	 * Returns vacancy list if we have already queried for them and
	 * saved them or we retrieve them from API.
	 * @return array|boolean
	 */
	public function get_all_vacancies( $orderby = 'end_date' ) {
		if ( $this->vacancy_list === null ) {
			$this->vacancy_list = $this->get_all_vacancies_from_api();
		}

		// Order them.
		$this->order( $orderby );

		// Then return them.
		return $this->vacancy_list;
	}

	/**
	 * Retrieves single vacancy from API.
	 * @param  integer
	 * @return StdClass
	 */
	private function get_single_from_api( int $vacancy_id ) {
		// SimpleXML loads XML from URL.
		@$xml = simplexml_load_file(sprintf( self::$SINGLE_URL, $vacancy_id ), 'SimpleXMLElement', LIBXML_NOWARNING);

		// Check if all is good.
		if ( $xml ) {
			// Return as StdClass.
			return $this->convert_xml_to_obj( $xml );
		}
	}

	/**
	 * Retrieves all available vacancies from API.
	 * @return array|boolean
	 */
	private function get_all_vacancies_from_api() {
		// SimpleXML loads XML from URL.
		@$xml = simplexml_load_file(self::$LIST_URL, 'SimpleXMLElement', LIBXML_NOWARNING);

		// Check if all is good.
		if ( $xml ) {
			// Loop through them, retrieve all relevant information
			// and save them as objects in an array that we can later return.
			$ret = array();
			foreach ( $xml->Vacancy as $vacancy ) {
				$ret[] = $this->convert_xml_to_obj( $vacancy );
			}
			return $ret;
		}

		// Otherwise something went wrong and we'll return false.
		else {
			return false;
		}
	}

	/**
	 * Converts XML data to a more user friendly object.
	 * @param  SimpleXMLElement
	 * @return StdClass
	 */
	private function convert_xml_to_obj( SimpleXMLElement $xml ) {
		return (object) array(
			'id'			=> (int) $xml->attributes()->id,
			'date_start'	=> (string) $xml->attributes()->date_start,
			'date_end'		=> (string) $xml->attributes()->date_end,
			'title'			=> (string) $xml->Versions->Version->Title,
			'description'	=> (string) $xml->Versions->Version->TitleHeading
		);
	}

	/**
	 * Sorts the list of vacancies based on in parameter.
	 *
	 * Valid sorting options are (as of now):
	 * 1. title
	 * 2. date_end
	 * 3. date_start
	 *
	 * All options are ascending.
	 * 
	 * @param  string String representation of what
	 * @return void
	 */
	private function order( $orderby ) {
		switch ( $orderby ) {
			case 'title':
				usort( $this->vacancy_list, function($a, $b) {
					return strcmp( $a->title, $b->title );
				} );
			break;

			case 'date_end':
				usort( $this->vacancy_list, function($a, $b) {
					return strtotime( $a->date_end ) - strtotime( $b->date_end );
				} );
			break;

			case 'date_start':
				usort( $this->vacancy_list, function($a, $b) {
					return strtotime( $a->date_start ) - strtotime( $b->date_start );
				} );
			break;
		}
	}

}