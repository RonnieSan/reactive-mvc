<?php
namespace Controllers;

Class Test extends \Reactive\Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index()	{
		echo 'This is the test page.';
	}

	// The 404 error page
	public function tester($one = NULL, $two = NULL, $three = NULL) {
		echo "One: $one<br />";
		echo "Two: $two<br />";
		echo "Three: $three<br />";
		echo 'This is the tester';
	}

	public function tester_post() {
		echo 'Getting';
	}

}