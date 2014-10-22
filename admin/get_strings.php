<?php

class PLL_Get_Strings {
	private $path;
	public $files = array();

	/* 
		@$strings 
		used to return all strings as an array of arrays 
			line:  line of code where it's found
			string: string
			domain: domain
			file: file
	*/
	public $strings = array(); 


	public function __construct($path_to_analyze) {
		// require potx (from drupal)
		require_once('potx.inc');

		$this->path = $path_to_analyze;

		$this->files = pll_get_files_in_path('*.php', $path_to_analyze);
	}

	public function analyze_files($file) {
		$self = $this;
		$callback_string = function($string, $domain, $file, $line) use ($self) { return $self->add_string($string, $domain, $file, $line); } ;

		_potx_process_file($file, 0, $callback_string,'_potx_save_version', POTX_API_7);
	}

	public function add_string($string, $domain, $file, $line) {
		$this->strings[] = array(
			'string' 	=> $string,
			'domain' 	=> $domain,
			'file'		=> $file,
			'line'		=> $line
		);
	}
}

// utils
function pll_get_files_in_path($pattern, $path, $flags = 0) {
	$paths = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
	$files = glob($path.$pattern, $flags);
	foreach ($paths as $path) {
    	$files = array_merge($files,pll_get_files_in_path($pattern, $path, $flags));
	}
	return $files;
}