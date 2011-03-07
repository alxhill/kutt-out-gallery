<?php

// So first we include the zebra class
require 'ZI/ZI.php';

class Zebra 
{    
	// Now we're going to define a class variable to hold our zebra
	private $zebra;
	
	// Constructor
	function __contruct()
	{
		// We don't really have to do much here. Just set up our private zebra
		$this->zebra = new Zebra_Image();
	}

	public function init()
	{
		$this->zebra = new Zebra_Image();
	}

	// Now we create a setup function
	public function setup($source, $target, $options = array())
	{
		$this->init();
		// indicate a source image (a GIF, PNG or JPEG file)
		$this->zebra->source_path = $source;

		// indicate a target image
		$this->zebra->target_path = $target;
		
		// setup other random options and shit
		foreach($options as $option=>$value)
		{
			$this->zebra->{$option} = $value;    
		}
	}

	// So now we can wrap our methods
	public function crop($start_x, $start_y, $end_x, $end_y)
	{
		return $this->zebra->crop($start_x, $start_y, $end_x, $end_y);
	}
	
	public function resize ( $width = 0, $height = 0, $method = ZEBRA_IMAGE_BOXED, $bgcolor = 'FFFFFF')
	{
		return $this->zebra->resize($width, $height, $method, $bgcolor);
	}
}