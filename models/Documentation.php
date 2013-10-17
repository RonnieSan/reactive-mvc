<?php
// USER MODEL

namespace Models;

Class Documentation extends \Models\Model
{
	
	public $columns = array('ID', 'appID', 'slug', 'title', 'type', 'content');
	public $ID;
	public $appID;
	public $slug;
	public $title;
	public $type;
	public $content;

	public $table = 'documentation';

	public function __construct() {
		parent::__construct();
	}
}
