<?php
namespace Controllers\Admin;

Class Login extends \Reactive\Public_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{
		$this->app->render('login.php');
	}

	// The login page
	public function index__post() {
		$this->app->user = new \Models\User();
		if ($this->app->user->authenticate($_POST['username'], $_POST['password'])) {
			$this->app->redirect('/admin');
		}

		$this->app->flashNow('error', 'The login information you entered was incorrect.');
		$this->app->render('login.php');
	}

}