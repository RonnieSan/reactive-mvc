<?php

// Generate a download key
if (!function_exists('generate_key')) {
	function generate_key($productID, $email) {
		$key = md5('4UHH557' . $productID . $email);
		return $key;
	}
}