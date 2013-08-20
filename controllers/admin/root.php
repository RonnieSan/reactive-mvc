<?php
namespace Controllers\Admin;

Class Root extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{
		$this->app->render('admin/dashboard.php');
	}

}