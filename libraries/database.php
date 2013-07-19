<?php
namespace Libraries;

// This class handles connecting to and querying a database
Class Database
{

	public $app;
	public $result;

	protected $_db;

	// Instantiate the database class
	public function __construct() {

		// Get an instance of the app
		$this->app = \Reactive\App::getInstance();

	}

	// Connect to the database
	public function connect($host = NULL, $database = NULL, $username = NULL, $password = NULL) {

		// Set the DB options
		if (is_array($host)) {
			$dbConfig = $host;
			$dbType   = $dbConfig['type'];
			$host     = $dbConfig['host'];
			$database = $dbConfig['database'];
			$username = $dbConfig['username'];
			$password = $dbConfig['password'];
		} else {
			$dbType   = $this->app->config('db.type') ?: 'mysql';
			$host     = $host ?: $this->app->config('db.host');
			$database = $database ?: $this->app->config('db.database');
			$username = $username ?: $this->app->config('db.username');
			$password = $password ?: $this->app->config('db.password');
		}

		try {

			// Create a database handle
			switch ($dbType) {

				// MSSQL, Sybase, DBLib
				case 'mssql':
				case 'sybase':
					$this->_db = new \PDO("{$dbType}:host={$host};dbname={$database}, {$username}, {$password}");
					break;

				case 'sqlite':
					$this->_db = new \PDO("sqlite:{$this->app->config('db.path')}");

				// Default to MySQL
				default:
					$this->_db = new \PDO("{$dbType}:host={$host};dbname={$database}", $username, $password);
					break;

			}

		} catch(PDOException $e) {

			// Print the error message
			echo $e->getMessage();

		}

	}

	// Close the databse connection
	public function close() {
		$this->_db = NULL;
	}

	// Set the error-handling mode
	// Possible options are: SILENT, WARNING, EXCEPTION
	public function set_error_mode($mode) {

		$mode = strtoupper($mode);
		$this->_db->setAttribute(\PDO::ATTR_ERRMODE, constant("\PDO::ERRMODE_{$mode}"));
		
	}

	// Create a new query builder
	public function query_builder() {
		return new Query();
	}

	// Run a query
	public function exec($query) {
		$result = $this->_db->query($query);
		$output = new Result($result);

		return $output;
	}

	// Alias for exec
	public function query($query) {
		return $this->exec($query);
	}

	// Insert insert a single record
	public function insert($table, $data) {

		$fields = array();
		$values = array();

		// Sanitize the data
		foreach ($data as $field => $value) {
			$fields[] = "{$field}";

			// Keep NULL values as NULL
			if (is_null($value)) {
				$value = 'NULL';
			}

			// Quote strings
			elseif (!is_numeric($value)) {
				$value = $this->_db->quote($value);
			}

			$values[] = "{$value}";
		}

		$fields = implode(',', $fields);
		$values = implode(',', $values);

		// Execute the query
		$this->_db->query("INSERT INTO {$table} ({$fields}) VALUES ({$values})");

		// Return the ID of the row we just inserted
		return $this->_db->lastInsertId();
		
	}

	// Do a bulk insert
	// *** Make sure all the rows in the $values array are in the same order and have the same keys
	public function insert_bulk($table, $values, $fields = NULL) {

		// Extract field names from the values array
		if (is_assoc($values[0])) {
			$fields = array_keys($values[0]);
		}

		// Get the field names if they were passed in
		else {

			// Make sure the fields are an array
			if (is_string($fields)) {
				$fields = explode(',', $fields);
			}

		}

		// Trim whitespace from the field names
		array_walk($fields, function(&$value) {
			$value = trim($value);
		});

		var_dump($values);

		// Create the values portion for the queries
		foreach ($fields as $field) {
			$namedValues[]   = ":{$field}";
			$unnamedValues[] = '?';
		}

		// Create the query based on what type of data was passed in
		if (is_assoc($values[0])) {

			// Named query
			$statement = $this->_db->prepare("INSERT INTO {$table} (" . implode(',', $fields) . ") VALUES (" . implode(',', $namedValues) . ")");

		} else {

			// Unnamed query
			$statement = $this->_db->prepare("INSERT INTO {$table} (" . implode(',', $fields) . ") VALUES (" . implode(',', $unnamedValues) . ")");

		}

		// Create an object to keep track of successful inserts and failures
		$results = new \stdClass;			
		$results->insertCount = 0;
		$results->errorCount  = 0;

		// Insert the values
		foreach ($values as $data) {
			if ($statement->execute($data)) {
				$results->insertCount++;
			} else {
				$results->errorCount++;
			}
		}

		// Return the success and error count
		return $results;

	}

	// Update some data
	public function update($table, $data, $where) {

		$setters = array();

		foreach ($data as $field => $value) {

			// Keep NULL values as NULL
			if (is_null($value)) {
				$value = 'NULL';
			}

			// Quote strings
			elseif (!is_numeric($value)) {
				$value = $this->_db->quote($value);
			}

			$setters[] = "{$field}={$value}";
		}

		$setters = implode(',', $setters);

		// Execute the query
		$count = $this->_db->exec("UPDATE {$table} SET {$setters} WHERE {$where}");

		// Return the number of rows affected
		return $count;

	}

	// Delete some data
	public function delete($table, $where) {
		
		// Execute the query
		$count = $this->_db->exec("DELETE FROM {$table} WHERE {$where}");

		// Return the number of rows affected
		return $count;

	}

	// Run a transaction
	// Works with MySQL
	public function transaction($queries) {

		// Make sure we are using MySQL and the error mode will throw exceptions
		if ($this->app->config('db.type') === 'mysql' && $this->_db->getAttribute(\PDO::ATTR_ERRMODE) === \PDO::ERRMODE_EXCEPTION) {

			try {

				// Start the transaction
				$this->_db->beginTransaction();

				// Loop through the queries
				foreach ($queries as $query) {
					$this->exec($query);
				}

				// Commit the transaction
				$this->_db->commit();

				return TRUE;


			} catch(PDOException $e) {

				// Rollback the transaction
				$this->_db->rollBack();

				return FALSE;

			}

		}

	}

}

// The result class
Class Result
{

	private $_result;

	public function __construct($result) {
		$this->_result = $result;
	}

	// Return the results as an array
	// Types can be ASSOC|BOTH|CLASS|OBJ|LAZY|NUM|FUNC
	public function fetch_all($type = 'ASSOC', $obj = NULL) {

		$type = constant("PDO::FETCH_{$type}");
		return $this->_result->fetchAll($type);

	}

	// Fetch the next row of the results
	public function fetch($type = 'ASSOC', $obj = NULL) {

		$type = constant("PDO::FETCH_{$type}");
		return $this->_result->fetch($type, $obj);

	}

	// Get the total number of columns
	public function column_count() {
		return $this->_result->columnCount();
	}

	// Free the result resource
	public function free() {
		$this->_result = NULL;
	}

}