<?php
// USER MODEL

namespace Models;

Class User extends \Reactive\Model
{
	
	public $columns = array('userID', 'username', 'email', 'password', 'firstName', 'lastName', 'lastLogin', 'token');
	public $table   = 'users';

	public function __construct() {
		parent::__construct();

		if (isset($_SESSION['userID'])) {
			$this->load($_SESSION['userID']);
		}
	}

	// Authenticate a user
	public function authenticate($username, $password) {

		// Get the user with a matching username
		$this->load($username);

		if (empty($this->userID)) {
			return FALSE;
		}

		if ($this->password === md5($password)) {

			// Set the userID in the session
			$_SESSION['userID'] = $this->userID;

			// Set the last login time
			$this->lastLogin = time();

			// Get the agent string and create a token from it
			$agent = $this->app->request()->getUserAgent();
			$this->token = md5('salty' . $this->userID . $agent);

			// Save the user
			$this->save();
			return TRUE;
		}

		return FALSE;

	}

	// Validate the user token
	public function validate_token() {

		if (isset($this->token)) {
			// Create a token from the current user and check it against the DB
			$agent = $this->app->request()->getUserAgent();
			if ($this->token == md5('salty' . $this->userID . $agent)) {
				return TRUE;
			}
		}

		return FALSE;

	}

	// Load the user
	public function load($userID) {

		// Build the query
		if (is_numeric($userID)) {
			$query = 'SELECT * FROM users WHERE userID = ' . $userID;	
		} else {
			$query = 'SELECT * FROM users WHERE username = "' . $userID . '"';
		}

		// Get the user with a matching username
		$users = $this->_db->query($query);
		$user = $users->fetch();

		if ($user) {
			foreach ($user as $key => $value) {
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
		if (!empty($this->userID)) {
			$this->_db->update($this->table, $data, 'userID = ' . $this->userID);
		}

		// Insert a new record
		else {
			$this->_db->insert($this->table, $data);
		}

		return TRUE;

	}

	// Log the user out
	public function logout() {
		unset($_SESSION['userID']);

		$this->token = NULL;
		$this->save();
	}
}
