<?php
namespace Database;

class Query
{

	private $_pieces = array();
	private $_query;

	// --------------------------------------------------
	// SELECT QUERIES
	
	// Select all fields from a table
	public function get($table, $limit = NULL, $offset = NULL) {

		$query = "SELECT * FROM {$table}";

		if ($limit !== NULL) {
			$query .= " LIMIT {$limit}";
		}

		if ($offset !== NULL) {
			$query .= ", {$offset}";
		}

		$this->_query = $query;

		return $this;

	}

	public function select($table = NULL, $fields = NULL, $where = NULL) {

		$this->_pieces['operation'] = "SELECT";
		$this->_pieces['table']     = $table;

		// Wrap the fields in an array if it's not
		if (!is_array($fields)) {
			$fields = array($fields);
		}

		foreach ($fields as $field) {
			array_extend($this->_pieces['fields'], $fields);
		}

		if (!is_array($where)) {
			$where = array($where);
		}

		$this->where($where);

		return $this;

	}

	// Alias for select
	public function fields() {
		
	}

	// Set the table for the query
	public function from($table) {

	}

	// Alias for from
	public function table($table) {

	}

	// Add a join statement to the query
	public function join($table, $match, $type) {

	}

	// Add a condition to the query
	public function where($key, $value, $type) {

	}

	// Add an optional conditional to the query
	public function or_where($key, $value, $type) {

	}

	// Add a group by statement to the query
	public function group_by() {

	}


	public function having() {

	}

	public function distinct() {

	}

	public function order_by() {

	}

	public function limit() {

	}

	// Alias for limit
	public function page() {

	}
	
	// END SELECT QUERIES
	// --------------------------------------------------

	
	// --------------------------------------------------
	// INSERT QUERIES

	// Setup an insert 
	public function set($key, $value = NULL) {

	}
	
	public function insert($table = NULL, $data = NULL) {

		// BULK INSERT
		// If the data passed in is not an associative
		// array, we can assume this is a bulk insert
		if (!is_assoc($data)) {

		}
	}
	
	// END INSERT QUERIES
	// --------------------------------------------------


	// --------------------------------------------------
	// UPDATE QUERIES
	
	public function update($table = NULL, $data = NULL, $where = NULL) {

	}

	public function insert_or_update() {

	}
	
	// END UPDATE QUERIES
	// --------------------------------------------------


	// --------------------------------------------------
	// DELETE QUERIES
	
	public function delete($table = NULL, $where = NULL) {

	}

	public function empty_table($table) {

	}
	
	// END DELETE QUERIES
	// --------------------------------------------------


	// --------------------------------------------------
	// RUN QUERIES
	
	// Build and return a query
	public function return() {

	}
	
	// END RUN QUERIES
	// --------------------------------------------------


	// --------------------------------------------------
	// QUERY 'CACHING'
	
	// Save the current pieces of a query
	public function save() {

	}

	// Use the pieces of a saved query
	public function use() {

	}

	// Clear the query pieces
	public function clear() {

	}
	
	// END QUERY 'CACHING'
	// --------------------------------------------------
}