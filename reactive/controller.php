<?php
namespace Reactive;

Class Controller
{

	public $view;

	protected $app;
	protected $args;

	public function __construct($app) {
		$this->app = $app;

		// Load the reactive view class
		$this->view = new \Reactive\View($app);
	}

}

Class Public_Controller extends Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

}