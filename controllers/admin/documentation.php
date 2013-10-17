<?php
namespace Controllers\Admin;

Class Documentation extends \Reactive\Private_Controller
{

	public function __construct($app) {
		parent::__construct($app);
	}

	// The documentation overview page
	public function index()	{

		$result = $this->app->db->query('SELECT * FROM apps ORDER BY appName');
		$apps   = $result->fetch_all('CLASS');

		$this->app->view->set_params('apps', $apps);

		$this->app->render('admin/documentation/default.php');
	}

	// The documentation app page
	public function app($ID) {

		// Load the app
		$app = new \Models\App();
		$app->ID = $ID;
		$app->load();

		$this->app->view->set_params('app', to_array($app));

		// Load the documentation list
		$result = $this->app->db->query('SELECT * FROM documentation WHERE appID = ' . $ID . ' ORDER BY title');
		$docs   = $result->fetch_all('CLASS');

		$this->app->view->set_params('docs', $docs);

		$this->app->render('admin/documentation/app.php');
	}

	// The documentation edit page
	public function edit($appID, $ID) {
		$doc = new \Models\Documentation();
		$doc->ID = $ID;
		$doc->load();

		$this->app->view->set_params(array(
			'appID' => $appID,
			'doc'   => to_array($doc)
		));

		$this->app->render('admin/documentation/edit.php');
	}

	// Save the edited documentation
	public function edit__post() {
		$doc = new \Models\documentation();
		$doc->create($this->app->request()->post());
		$doc->save();

		// It was a new app
		if ($doc->ID == 0) {
			$this->app->flash('message', '<p>Your doc was successfully created.</p>');
		}

		// We updated an existing app
		else {
			$this->app->flash('message', '<p>Your doc was successfully updated.</p>');
		}

		$this->app->redirect('/admin/documentation/app/' . $doc->appID);
	}

}