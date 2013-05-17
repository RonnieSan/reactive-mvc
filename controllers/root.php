<?php
namespace Controllers;
 
Class Root extends \Reactive\Controller
{

	// THe home page
	public function index()	{
		$this->render('index.php', array('name' => 'Ronnie'));
	}

	// The 404 error page
	public function error404() {
		echo 'Oops!  404 Error!';
	}

}