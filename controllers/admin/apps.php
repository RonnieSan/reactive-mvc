<?php
namespace Controllers\Admin;

Class Apps extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The App Page
	public function index()	{

		// Get a list of the apps
		$query = $this->app->db->query('SELECT * FROM apps ORDER BY appName');
		$apps  = $query->fetch_all('CLASS', '\Models\App');

		// Pass the list into the page params
		$this->app->view->set_params('apps', $apps);

		$this->app->render('admin/apps/default.php');
	}

	// Edit App Page
	public function edit($slug, $name = NULL) {

		$app = new \Models\app();

		if ($slug != 'new') {
			$app->load_by_slug($slug);
		}

		// Pass the list into the page params
		$this->app->view->set_params('app', to_array($app));

		$this->app->render('admin/apps/edit.php');
	}

	// Submit the App Page
	public function edit__post() {

		// var_dump($this->app->request()->post());
		$app = new \Models\app();
		$app->create($this->app->request()->post());
		$app->save();

		// It was a new app
		if ($app->ID == 0) {
			$this->app->flash('message', '<p>Your app was successfully created.</p>');
		}

		// We updated an existing app
		else {
			$this->app->flash('message', '<p>Your app was successfully updated.</p>');
		}

		$this->app->redirect('/admin/apps');

	}

}