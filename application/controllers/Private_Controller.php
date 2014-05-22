<?php

namespace Controllers;

// Extend this class for password-protected pages
abstract class Private_Controller extends \Reactive\Controller
{

	public function __construct() {
		parent::__construct();

		// Authenticate the user
		$user = new \Models\User();
		if (!$user->validate_token()) {
			$this->app->redirect('/admin/user/login');
		} else {
			$this->app->user = $user;
		}
	}

}