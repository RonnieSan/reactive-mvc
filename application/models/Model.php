<?php
namespace Models;

abstract class Model extends \Reactive\Model
{
	public $id;
	public $data = array();

	protected $_table;

	public function __construct($id = NULL) {
		parent::__construct();

		if ($id !== NULL) {
			$this->id = $id;
			$this->fetch();
		}
	}

	// Return a reference to the app object
	protected function app() {
		return \Reactive\App::getInstance();
	}

	// Set object properties
	public function set($data, $value = NULL) {
		if (!is_array($data)) {
			$data = array($data => $value);
		}

		foreach ($data as $key->$value) {
			$this->data[$key] = $value;
		}
	}

	// Get an object property
	public function get($key) {
		return d($this->data[$key], NULL);
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

}