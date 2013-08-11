<?php
namespace Controllers\Admin;

Class Root extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{

		echo 'This is the test page.';
	}

	// The login page
	public function login() {
		echo 'Please log in.';
	}

	// Process the login page
	public function login__post() {

	}

}