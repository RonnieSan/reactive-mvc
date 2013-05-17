<?php

// Autoload classes
function reactive_autoloader($class) {

	// Get the base directory
	$basedir = dirname(__DIR__);

	// Build a path from the namespace
	$path = strtolower(str_replace('\\', '/', $class));

	if (file_exists("{$basedir}/{$path}.php")) {

		// Autoload namespaced classes
		require_once "{$basedir}/{$path}.php";

	}

}

spl_autoload_register('reactive_autoloader');