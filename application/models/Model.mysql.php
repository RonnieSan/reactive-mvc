<?php
namespace Models;

class Model extends \Reactive\Model
{

	public $columns = array();
	public $ID;
	public $table;

	public function __construct($ID = NULL) {
		parent::__construct();

		if ($ID !== NULL) {
			$this->ID = $ID;
			$this->load();
		}
	}

	// Set object properties from array
	public function create($data) {
		foreach ($data as $key => $value) {
			if (in_array($key, $this->columns)) {
				$this->$key = $value;
			}
		}
	}

	// Load the object from the database
	public function load() {
		if (!empty($this->ID)) {
			$result = $this->_db->query('SELECT * FROM ' . $this->table . ' WHERE ID = ' . $this->ID);
			$object = $result->fetch();

			if (is_array($object)) {
				foreach ($this->columns as $column) {
					$this->$column = $object[$column];
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	// Save the object to the database
	public function save() {

		$data = array();

		foreach ($this->columns as $column) {
			$data[$column] = $this->$column;
		}

		if (!empty($this->ID)) {
			$result = $this->_db->update($this->table, $data, 'ID = ' . $this->ID);
		} else {
			unset($data['ID']);
			$result = $this->_db->insert($this->table, $data);
		}

		if ($result !== FALSE) {
			return TRUE;
		}

		return FALSE;

	}

	// Delete the object from the database
	public function delete() {
		$result = $this->_db->delete($this->table, 'ID = ' . $this->ID);

		if ($result > 0) {
			return TRUE;
		}

		return FALSE;
	}

}