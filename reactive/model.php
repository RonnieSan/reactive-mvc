<?php
namespace Reactive;

Class Model
{

	public $app;
	public static $methods = array();

	protected $_db;

	function __construct() {
		// Get an instance of the App
		$this->app = \Reactive\App::getInstance();

		// Get a database connection
		$this->_db = \Libraries\Database::getInstance();
	}
	
	// Register a new method
	public static function registerMethod($method) {
		self::$methods[] = $method;
	}
	
	// Call a dynamic method
	public function __call($method, $args) {
	    if (in_array($method, self::$methods)) {
			$obj = new Facade($this);
			$fields = array();
			foreach (array_keys(get_class_vars(__CLASS__)) as $field) {
				if ($field != 'methods') {
	  				$fields[$field] = &$this->$field;
				}
			}
			$obj->registerFields($fields);
			array_unshift($args, $obj);
			return call_user_func_array($method, $args);
	    }
	}

	
	// Extract arguments
	function extractArgs($args) {
		if (is_string($args)) {
			parse_str($args, $vars);
			$args = $vars;
		}
		if (is_array($args)) {
			foreach ($args as $key => $value) {
				$this->{$key} = $value;
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// Destroy the object
	function __destruct() {
		
	}
}

class Facade
{
	private $object = NULL;
	private $fields = array();
	private $arrays = array();

	function __construct($obj) {
		$this->object = $obj;
	}

	public function __get($var) {
		if (in_array($var, array_keys($this->fields))) {
			return $this->fields[$var];
		} else {
			return $this->object->$var;
		}
	}

	public function __set($var, $val) {
		if (in_array($var, array_keys($this->fields))) {
			$this->fields[$var] = $val;
		} else {
			$this->$object->$var = $val;
		}
	}

	public function __isset($var) {
		if (in_array($var, array_keys($this->fields))) {
			return TRUE;
		}
		return isset($this->object->$var);
	}

	public function __unset($var) {
		unset($this->object->$var);
		unset($this->fields[$var]);
	}

	public function registerFields(&$fields) {
		$this->fields = $fields;
	}

	function __call($method, $args) {
		return call_user_func_array(array($this->object, $method), $args);
	}
}