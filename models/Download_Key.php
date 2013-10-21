<?php
// USER MODEL

namespace Models;

Class Download_Key extends \Models\Model
{
	
	public $columns      = array('ID', 'customerID', 'productID', 'key', 'downloadCount', 'active', 'created');
	public $extraColumns = array('name', 'email', 'productName');
	public $ID;
	public $customerID;
	public $name;
	public $email;
	public $productID;
	public $productName;
	public $key;
	public $downloadCount;
	public $active;
	public $created;

	public $table = 'downloadKeys';

	public function __construct() {
		parent::__construct();
	}

	// Load the object from the database
	public function load() {
		if (!empty($this->ID)) {

			// Build the query
			$query = "SELECT k.*, c.name, c.email, p.productName 
				FROM downloadKeys k 
				LEFT JOIN customers c ON k.customerID = c.ID 
				LEFT JOIN products p ON k.productID = p.ID 
				WHERE k.ID = {$this->ID}";

			$result = $this->_db->query($query);
			$object = $result->fetch();

			if (is_array($object)) {
				foreach ($this->columns as $column) {
					$this->$column = $object[$column];
				}

				foreach ($this->extraColumns as $column) {
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
}
