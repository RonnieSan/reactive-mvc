<?php
namespace Controllers\Admin;

Class Documentation extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The documentation overview page
	public function index()	{
		$this->app->render('admin/dashboard.php');
	}

	// The documentation app page
	public function app($appName) {
		$app = new \Models\App($appName);

		$this->app->view->set_params('appName', $app->appName, 'None');

		$this->app->render('admin/documentation/app.php');
	}

	// The documentation edit page
	public function edit($pageID) {

		$this->app->render('admin/documentation/edit.php');
	}

}