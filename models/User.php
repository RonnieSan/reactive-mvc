<?php
// USER MODEL

namespace Models;

Class User extends \Models\Model
{
	
	public $columns = array('ID', 'username', 'email', 'password', 'firstName', 'lastName', 'lastLogin', 'token');

	public $ID;
	public $username;
	public $email;
	public $password;
	public $firstName;
	public $lastName;
	public $lastLogin;
	public $token;

	public $table   = 'users';

	public function __construct() {
		parent::__construct();

		if (isset($_SESSION['userID'])) {
			$this->ID = $_SESSION['userID'];
			$this->load();
		}
	}

	// Authenticate a user
	public function authenticate($username, $password) {

		// Get the user with a matching username
		$this->load_user($username);

		if (empty($this->ID)) {
			return FALSE;
		}

		if ($this->password === md5($password)) {

			// Set the userID in the session
			$_SESSION['userID'] = $this->ID;

			// Set the last login time
			$this->lastLogin = time();

			// Get the agent string and create a token from it
			$agent = $this->app->request()->getUserAgent();
			$this->token = md5('salty' . $this->ID . $agent . session_id());

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
			if ($this->token == md5('salty' . $this->ID . $agent . session_id())) {
				return TRUE;
			}
		}

		return FALSE;

	}

	// Load the user
	public function load_user($ID) {

		// Build the query
		if (is_numeric($ID)) {
			$query = 'SELECT * FROM users WHERE ID = ' . $ID;	
		} else {
			$query = 'SELECT * FROM users WHERE username = "' . $ID . '"';
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

	// Log the user out
	public function logout() {
		unset($_SESSION['userID']);

		$this->token = NULL;
		$this->save();
	}
}
