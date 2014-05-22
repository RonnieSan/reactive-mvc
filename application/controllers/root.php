<?php
namespace Controllers;

class Root extends \Controllers\Public_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The home page
	public function index($name = NULL)	{

		// $model = new \Models\Model();
		// $model->test();

		$data['name'] = d($name, 'John Doe');

		$this->app->render('home.php', $data);
	}

	// The home page
	public function test()	{
		
		$this->app->render('test.php', array('name' => 'Hingle McCringleberry'));

	}

}