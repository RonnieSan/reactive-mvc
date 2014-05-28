<?php
// SAMPLE MODEL

namespace Models;

class User extends \Models\Model
{
	

	public function __construct() {
		parent::__construct();
	}

	// Log the user in
	public function login($email, $password) {

	}

	// Check if the user is logged in
	public function is_logged_in() {

	}

	// Check if the user has particular permissions
	public function has_permission($permission) {
		
	}

	// Log the user out
	public function logout() {

	}

}