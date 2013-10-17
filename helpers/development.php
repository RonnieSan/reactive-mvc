<?php

// Get a value or return a default
if (!function_exists('get_var')) {
	function get_var(&$var, $default = NULL) {
		if (!isset($var)) {
			return $default;
		}
		return $var;
	}
}