<?php
namespace Controllers;

Class Root extends \Reactive\Public_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{
		$this->app->render('home.php');
	}

}