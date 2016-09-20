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
		return $this->vacancy_list->vacancies;
	}

	/**
	 * Returns all categories found in current vacancies list.
	 * @return array|boolean
	 */
	public function get_vacancies_categories() {
		if ( $this->vacancy_list === null ) {
			$this->vacancy_list = $this->get_all_vacancies_from_api();
		}

		// Return categories.
		return $this->vacancy_list->categories;
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
			return $this->convert_xml_to_obj( $xml, true );
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
			$ret = array(
				'categories'	=> array(),
				'vacancies'		=> array()
			);

			// Loop through them, read category and convert to object.
			foreach ( $xml->Vacancy as $vacancy ) {
				$category = $this->get_category_name( $vacancy );

				// Set category.
				// Set it to 1 if this is the first occurence, otherwise count it up.
				$ret[ 'categories' ][ $category ] = ( isset( $ret[ 'categories' ][ $category ] ) ) ?
					$ret[ 'categories' ][ $category ] + 1 : 1;

				// Convert to an object.
				$ret[ 'vacancies' ][] = $this->convert_xml_to_obj( $vacancy );
			}
			return (object) $ret;
		}

		// Otherwise something went wrong and we'll return false.
		else {
			return false;
		}
	}

	/**
	 * Returns Vacancy items category from XML.
	 * @param  SimpleXMLElement
	 * @return string
	 */
	private function get_category_name( SimpleXMLElement $xml ) {

		$category_name = (string) $xml->Versions->Version->Categories->Item[0];

		return (string) ( !empty( $category_name ) ) ?  $category_name : 'Okategoriserad';
	}

	/**
	 * Converts XML data to a more user friendly object.
	 * @param  SimpleXMLElement
	 * @return StdClass
	 */
	private function convert_xml_to_obj( SimpleXMLElement $xml, $detailed = false ) {
		$ret = array(
			'id'			=> (int) $xml->attributes()->id,
			'date_start'	=> (string) $xml->attributes()->date_start,
			'date_end'		=> (string) $xml->attributes()->date_end,
			'title'			=> (string) $xml->Versions->Version->Title,
			'description'	=> (string) $xml->Versions->Version->TitleHeading,
			'category'		=> $this->get_category_name( $xml ),
			'sanitized_cat'	=> sanitize_title( $this->get_category_name( $xml ) )
		);

		// Add some more info if XML is detailed.
		if ( $detailed ) {
			// Add some more information.
			$ret['description']		= (string) $xml->Versions->Version->Description;
			$ret['sub-title']		= (string) $xml->Versions->Version->TitleHeading;

			// Save contact persons in an array.
			$ret['contact_persons']	= array();

			// Add contact persons.
			$c = 0;
			foreach ( $xml->Departments->Department->ContactPersons->ContactPerson as $contact ) {
				$ret['contact_persons'][ $c ] = array(
					'name'			=> (string) $contact->CommonName,
					'phonenumbers'	=> array()
				);

				// Loop through telephone numbers and add them.
				foreach ( $contact->Telephone as $phone ) {
					$phone_type = (string) $phone->attributes()->type;
					$index = sprintf( '%s_phone', $phone_type );
					$ret['contact_persons'][ $c ]['phonenumbers'][ $index ] = (string) $phone;
				}

				// Convert to object.
				$ret['contact_persons'][ $c ] = (object) $ret['contact_persons'][ $c ];

				$c++;
			}

			// Add link.
			$ret['apply_link'] 		= $xml->Departments->Department->ApplicationURL;
		}

		// Return it as an object.
		return (object) $ret;
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