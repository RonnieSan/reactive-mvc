<?php
// REACTIVE FRAMEWORK
// Core Functions

// ------------------------------
// MISC FUNCTIONS

// Call an arbitrary function and pass variables to it
// This is needed because call_user_func_array is terribly slow

function call_func($function, $args = array()) {

	// Count the number of arguments passed in
	$argCount = count($args);

	// If an array was passed in...
	if (is_array($function)) {

		$class  = $function[0];
		$method = $function[1];

		// call_user_func_array is slow, this is slightly faster
		switch ($argCount) {

			case 0: 
				$class->{$method}();
				break;

			case 1:
				$class->{$method}($args[0]);
				break;

			case 2:
				$class->{$method}($args[0], $args[1]);
				break;

			case 3:
				$class->{$method}($args[0], $args[1], $args[2]);
				break;

			case 4:
				$class->{$method}($args[0], $args[1], $args[2], $args[3]);
				break;

			case 5:
				$class->{$method}($args[0], $args[1], $args[2], $args[3], $args[4]);
				break;

			default:
				call_user_func_array(array($class->method), $args);
				break;

		}

	} else {
	
		// call_user_func_array is slow, this is slightly faster
		switch ($argCount) {

			case 0: 
				$function();
				break;

			case 1:
				$function($args[0]);
				break;

			case 2:
				$function($args[0], $args[1]);
				break;

			case 3:
				$function($args[0], $args[1], $args[2]);
				break;

			case 4:
				$function($args[0], $args[1], $args[2], $args[3]);
				break;

			case 5:
				$function($args[0], $args[1], $args[2], $args[3], $args[4]);
				break;

			default:
				call_user_func_array($function, $args);
				break;

		}

	}

}

// Check if an array is associative
function is_assoc($array) {
	return (bool)count(array_filter(array_keys($array), 'is_string'));
}

// END MISC FUNCTIONS
// ------------------------------