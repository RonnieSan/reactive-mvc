<?php
// REACTIVE FRAMEWORK
// Base Controller Classes

namespace Reactive;

abstract class Controller
{

	public $view;

	protected $app;

	public function __construct() {
		$this->app  = \Reactive\App::getInstance();
		$this->view =& $this->app->view;
	}

}