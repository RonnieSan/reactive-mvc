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
		$query = $this->app->db->query('SELECT * FROM downloadKeys ORDER BY created DESC LIMIT ' . $firstRecord . ',50');
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

		// Pass the list into the page params
		$this->app->view->set_params('key', to_array($key));

		$this->app->render('admin/download-keys/edit.php');
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