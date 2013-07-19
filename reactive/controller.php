<?php
// REACTIVE FRAMEWORK
// Base Controller Classes

namespace Reactive;

Class Controller
{

	public $view;

	protected $app;
	protected $args;

	public function __construct() {
		$this->app = \Reactive\App::getInstance();
	}

}

// Extend this class for password-protected pages
Class Private_Controller extends Controller{

	public function __construct() {
		parent::__construct();
	}

}

// Extend this class for public-facing pages
Class Public_Controller extends Controller
{

	public function __construct() {
		parent::__construct();
	}

}