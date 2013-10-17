<?php
namespace Controllers\Admin;

Class Products extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The App Page
	public function index()	{

		// Get a list of the apps
		$query = $this->app->db->query('SELECT * FROM products ORDER BY productName');
		$products  = $query->fetch_all('CLASS', '\Models\Product');

		// Pass the list into the page params
		$this->app->view->set_params('products', $products);

		$this->app->render('admin/products/default.php');
	}

	// Edit App Page
	public function edit($ID) {

		$product = new \Models\product();
		$product->ID = $ID;
		$product->load();

		// Pass the list into the page params
		$this->app->view->set_params('product', to_array($product));

		$this->app->render('admin/products/edit.php');
	}

	// Submit the App Page
	public function edit__post() {

		// var_dump($this->app->request()->post());
		$product = new \Models\product();
		$product->create($this->app->request()->post());
		$product->save();

		// It was a new app
		if ($product->ID == 0) {
			$this->app->flash('message', '<p>Your product was successfully created.</p>');
		}

		// We updated an existing app
		else {
			$this->app->flash('message', '<p>Your product was successfully updated.</p>');
		}

		$this->app->redirect('/admin/products');

	}

}