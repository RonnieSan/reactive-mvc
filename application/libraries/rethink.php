<?php
namespace Libraries;

Class Rethink
{

	public $conn;
	public $dbName;
	public $db;

	protected $_db;

	// Instantiate the database class
	public function __construct($connect = TRUE) {

		// Get an instance of the app
		$this->app = \Reactive\App::getInstance();

		// Create a DB connection
		if ($connect) {
			$this->connect();
		}

	}

	// Create a connection to the database
	public function connect() {

		$this->conn   = \r\connect('localhost');
		$this->dbName = 'reactiveapps';

		$this->db = \r\db($this->dbName);

	}

	// Insert a record into the database
	public function insert($table, $data) {

		$result = $this->db->table($table)->insert($data)->run($this->conn);

	}

}