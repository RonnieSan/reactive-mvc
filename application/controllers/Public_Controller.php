<?php

namespace Controllers;

// Extend this class for public-facing pages
abstract Class Public_Controller extends \Reactive\Controller
{

	public function __construct() {
		parent::__construct();
	}

}