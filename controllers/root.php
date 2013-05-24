<?php
namespace Controllers;

Class Root extends \Reactive\Public_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{
		echo 'This is the home page';
	}

	// The 404 error page
	static function error_404() {
		echo 'Oops!';
	}

}