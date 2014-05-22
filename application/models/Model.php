<?php
namespace Models;

abstract class Model extends \Reactive\Model
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