<?php
namespace Reactive;

Class Controller extends \Slim\Slim
{

	public function __construct() {

		// Load the settings file
		$settings = require 'settings.php';

		// Run the slim constructor
		parent::__construct($settings);

	}

}