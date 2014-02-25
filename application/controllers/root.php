<?php
namespace Controllers;

Class Root extends \Reactive\Public_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{
		$this->app->render('home.php', array('name' => 'John Doe'));
	}

	// The home page
	public function test($name = "Someone Else", $var = NULL)	{
		$this->app->render('home.php', array('name' => $name));
	}

}