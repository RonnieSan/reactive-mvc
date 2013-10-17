<?php
// USER MODEL

namespace Models;

Class Product extends \Models\Model
{
	
	public $columns = array('ID', 'productName', 'slug', 'price', 'buyNowCode', 'active');
	public $ID;
	public $appName;
	public $slug;
	public $price;
	public $buyNowCode;
	public $active;

	public $table = 'products';

	public function __construct() {
		parent::__construct();
	}

	// Load an app by slug
	public function load_by_slug($slug) {

		// Build the query
		$query = 'SELECT * FROM products WHERE slug = "' . $slug . '"';

		// Get the user with a matching username
		$products = $this->_db->query($query);
		$product  = $products->fetch();

		if ($product) {
			foreach ($product as $key => $value) {
				$this->$key = $value;
			}

			return TRUE;
		}

		return FALSE;

	}
}
