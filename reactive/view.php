<?php

namespace Reactive;

Class View extends \Slim\View
{

	protected $_css    = array();
	protected $_js     = array('footer' => array());
	protected $_meta   = array();

	public $app;
	public $params = array();
	public $view;

	public function __construct() {
		parent::__construct();

		// Get an instance of the app
		$this->app = \Reactive\App::getInstance();

		// Create a reference to the view
    	$this->view = $this;
    }

	// ------------------------------
	// Meta tag methods

	// Add a meta field
	public function add_meta($key, $val = NULL, $type = 'name') {

		// Check if an array was passed in
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				foreach ($v as $property => $value) {
					$this->meta[$k][$property] = $value;
				}
			}
		}

		// Just add a single meta tag
		else {
			$this->meta[$type][$key] = $val;
		}

	}

	// Print all the stored meta fields
	public function print_meta() {
		
		// Loop through the types
		foreach ($this->_meta as $type => $tag) {

			// Loop through the keys
			foreach ($tag as $key => $val) {
				echo "<meta {$type}=\"{$key}\" content=\"{$val}\">" . PHP_EOL;
			}

		}

	}

	// End meta tag methods
	// ------------------------------


	// ------------------------------
	// Stylesheet methods

	// Add stylesheets to the _css array
	public function add_css($css) {
		
		// Wrap a string in an array
		if (!is_array($css)) {
			$css = array($css);
		}

		foreach ($css as $stylesheet) {
			$this->_add_stylesheet($stylesheet);
		}

	}

	// Add a stylesheet to the _css array
	protected function _add_stylesheet($stylesheet) {
		$this->_css[] = $stylesheet;
	}

	// Print out the HTML for the CSS links
	public function print_css() {

		// Create a template for the CSS link tags
		$cssLink = '<link rel="stylesheet" type="text/css" href="%s">';

		// Print all the non-browser-specific styles
		foreach ($this->_css as $stylesheet) {
			echo "\t" . sprintf($cssLink, $stylesheet) . PHP_EOL;
		}
	}

	// End stylesheet methods
	// ------------------------------


	// ------------------------------
	// Javascript methods
	
	// Add a script to the _js array
	public function add_js($js, $location = 'footer') {
		
		// Wrap a string in an array
		if (!is_array($js)) {
			$js = array($js);	
		}

		foreach ($js as $script) {
			$this->_add_js($script, $location);
		}
	}

	// Add a script to the _js array
	protected function _add_js($script, $location = 'footer') {
		$this->_js[$location][] = $script;
	}

	// Print the script tags for the JS files
	public function print_js($location = 'footer') {
		if(key_exists($location, $this->_js)){
			foreach ($this->_js[$location] as $script) {
				echo '<script src="' . $script . '"></script>' . PHP_EOL;
			}
		}
	}

	// End javascript methods
	// ------------------------------


	// ------------------------------
	// Param methods

	// Set the params for the view
	public function set_params($key, $value = NULL, $default = NULL) {
		if (is_array($key)) {
			foreach($key as $k => $v){
				$this->params[$k] = $v;
			}
		} else {
			if ($value !== NULL) {
				$this->params[$key] = $value;
			} else {
				$this->params[$key] = $default;
			}
		}
	}

	// Return the params
	public function get_params($key = NULL) {
		if (is_null($key)) {
			return $this->params;
		} else {
			if (isset($this->params[$key])) {
				return $this->params[$key];
			}
			return NULL;
		}
	}

	// End param methods
	// ------------------------------


	// ------------------------------
	// Render methods
	
	// Render a partial
	public function partial($template) {
		echo $this->render($template);
	}

	// Render a view file
	public function render($template) {

		// Get the template path
		$templatePathname = $this->getTemplatePathname($template);
		
		if (!file_exists($templatePathname)) {
			throw new \RuntimeException("View cannot render template '$templatePathname' -- template does not exist.");
		}

		// --------------------------------------------------
		// VIEW CONTROLLERS
		// If you want to use a client-side MVC like
		// Backbone, uncomment this section and it will
		// automatically include a JS file with a name
		// matching the view if it exists

		$viewControllerScript = str_replace('.php', '.js', $templatePathname);
		if (is_file($viewControllerScript)) {
			$this->add_js($viewControllerScript, 'footer');
		}
		
		// END VIEW CONTROLLERS
		// --------------------------------------------------

		// Set the view data with the params
		$this->appendData($this->params);

		// Extract the Slim framework template variables
		extract($this->data->all());

		// Render the HTML and save it to a variable
		ob_start();
		require $templatePathname;
		
		return ob_get_clean();

		// Return the rendered layout
		return $html;

	}

	// End render methods
	// ------------------------------

}
