<?php
namespace Controllers\Admin;

Class User extends \Reactive\Public_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function login()	{
		$this->app->render('admin/login.php');
	}

	// The login page
	public function login__post() {
		$this->app->user = new \Models\User();
		if ($this->app->user->authenticate($_POST['username'], $_POST['password'])) {
			$this->app->redirect('/admin');
		}

		$this->app->flashNow('error', 'The login information you entered was incorrect.');
		$this->app->render('admin/login.php');
	}

	public function logout() {
		$user = new \Models\User();
		$user->logout();

		$this->app->flash('error', 'You have been logged out.');
		$this->app->redirect('/admin/user/login');
	}

}