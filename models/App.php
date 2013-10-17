<?php
// USER MODEL

namespace Models;

Class App extends \Models\Model
{
	
	public $columns = array('ID', 'appName', 'slug');
	public $ID;
	public $appName;
	public $slug;

	public $table = 'apps';

	public function __construct() {
		parent::__construct();
	}

	// Load an app by slug
	public function load_by_slug($slug) {

		// Build the query
		$query = 'SELECT * FROM apps WHERE slug = "' . $slug . '"';

		// Get the user with a matching username
		$apps = $this->_db->query($query);
		$app  = $apps->fetch();

		if ($app) {
			foreach ($app as $key => $value) {
				$this->$key = $value;
			}

			return TRUE;
		}

		return FALSE;

	}
}
