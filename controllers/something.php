<?php
namespace Controllers;
 
Class Something extends \Reactive\Controller
{

	// THe home page
	public function awesome($one = NULL, $two = NULL, $three = NULL) {
		echo "Something awesome $one $two $three!";
	}

}