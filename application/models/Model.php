<?php
namespace Models;

Class Model extends \Reactive\Model
{

	public $data = array();
	public $id;
	public $table;

	public function __construct($id = NULL) {
		parent::__construct();

		if ($id !== NULL) {
			$this->id = $id;
			$this->fetch();
		}
	}

	// Set object properties from array
	public function create($data) {
		$this->data = $data;
	}

	public function test() {
		echo $this->_curl('http://www.reactivemvc.dev/test');
	}

	// Load the object from the database
	public function fetch() {
		if (!empty($this->id)) {
			
		}

		return FALSE;
	}

	// Save the object to the database
	public function save() {

	}

	// Delete the object from the database
	public function delete() {
		
	}

	protected function _curl($url, $method = 'GET', $data = NULL) {

		//  Initiate curl
		$ch = curl_init();

		// Disable SSL verification
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		// Follow and location headers
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		// Set the post data if applicable
		if ($data !== NULL) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		// Set the url
		curl_setopt($ch, CURLOPT_URL, $url);

		// Execute
		$result = curl_exec($ch);

		// Close the curl connection
		curl_close($ch);

		// Return the result
		return $result;

	}

}