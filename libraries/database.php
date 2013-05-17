<?php
namespace Libraries;

Class Database
{
	
	public function __construct() {
		echo 'New database.';
	}

	private function _connect() {

		try {
			$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}

	}

}