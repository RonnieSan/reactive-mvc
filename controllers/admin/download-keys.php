<?php
namespace Controllers\Admin;

Class Download_Keys extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The App Page
	public function index($page = 1) {

		// Get a list of the apps
		$firstRecord = ($page - 1) * 50;
		$query = $this->app->db->query('SELECT k.*, c.name as customerName, c.email, p.productName FROM downloadKeys k LEFT JOIN customers c ON k.customerID = c.ID LEFT JOIN products p ON k.productID = p.ID ORDER BY k.created DESC LIMIT ' . $firstRecord . ',50');
		$keys  = $query->fetch_all('CLASS', '\Models\Download_Key');

		// Pass the list into the page params
		$this->app->view->set_params('keys', $keys);

		$this->app->render('admin/download-keys/default.php');
	}

	// Edit App Page
	public function edit($ID) {

		$key = new \Models\Download_Key();
		$key->ID = $ID;
		$key->load();

		// Get a list of the products
		$productQuery = $this->app->db->query("SELECT ID, productName FROM products ORDER BY productName ASC");
		$products = $productQuery->fetch_all();

		$this->app->view->set_params('products', $products);

		// Pass the list into the page params
		$this->app->view->set_params('key', to_array($key));

		$this->app->render('admin/download-keys/edit.php');
	}

	// Submit the App Page
	public function edit__post() {

		// var_dump($this->app->request()->post());
		$key = new \Models\Download_Key();
		$key->create($this->app->request()->post());
		$key->save();

		// It was a new app
		if ($key->ID == 0) {
			$this->app->flash('message', '<p>Your key was successfully created.</p>');
		}

		// We updated an existing app
		else {
			$this->app->flash('message', '<p>Your key was successfully updated.</p>');
		}

		$this->app->redirect('/admin/download-keys');

	}

	public function get($productID, $email) {
		$this->app->load_helper('development');
		echo generate_key($productID, $email);
	}

}