<?php

namespace Database;

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

		// Set the type
		$type = constant("PDO::FETCH_{$type}");

		if ($type == 8) {
			if ($obj === NULL) {
				$obj = 'stdClass';
			}

			return $this->_result->fetchAll($type, $obj);
		} else {
			unset($obj);
		}
		
		return $this->_result->fetchAll($type);
	}

	// Fetch the next row of the results
	public function fetch($type = 'ASSOC', $obj = NULL) {

		if ($type == 'CLASS' && $obj !== NULL) {
			$this->_result->setFetchMode(8, $obj);
		}

		$type = constant("PDO::FETCH_{$type}");
		return $this->_result->fetch($type);

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