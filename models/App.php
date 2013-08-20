<?php
// USER MODEL

namespace Models;

Class App extends \Reactive\Model
{
	
	public $columns = array('appID', 'appName', 'buyNowCode');
	public $appID;
	public $appName;
	public $buyNowCode;

	public $table   = 'apps';

	public function __construct($appID = NULL) {
		parent::__construct();

		if ($appID !== NULL) {
			$this->load($appID);
		}
	}

	// Load an app
	public function load($appID) {

		// Build the query
		if (is_numeric($appID)) {
			$query = 'SELECT * FROM apps WHERE appID = ' . $appID;	
		} else {
			$query = 'SELECT * FROM apps WHERE appName = "' . $appID . '"';
		}

		// Get the user with a matching username
		$apps = $this->_db->query($query);
		$app  = $apps->fetch();

		if ($app) {
			foreach ($app as $key => $value) {
				$this->$key = $value;
			}

			return TRUE;
		}

		return FALSE;

	}

	// Save the user data
	public function save() {

		// Build the data array
		foreach ($this->columns as $columnName) {
			if (isset($this->$columnName)) {
				$data[$columnName] = $this->$columnName;
			}
		}

		// Update an existing record
		if (!empty($this->appID)) {
			$this->_db->update($this->table, $data, 'appID = ' . $this->appID);
		}

		// Insert a new record
		else {
			$this->_db->insert($this->table, $data);
		}

		return TRUE;

	}
}
