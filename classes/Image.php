<?php

class Image
{

	private $_file,
			$_file_name,
			$_file_array = array(),
			$_file_ext,
			$_file_temp,
			$_file_path,
			$_allowed = array('jpg', 'jpeg', 'png', 'gif'),
			$_allowed_ext,
			$_errors = array(),
			$_passed = false;

	public function __construct($file){
		$this->_file = $file;

		$this->_file_name = $this->_file['name'];
		$this->_file_array = explode('.', $this->_file_name);
		$this->_file_ext = strtolower(end($this->_file_array));
		$this->_file_temp = $this->_file['tmp_name'];
	}

	public function check(){
		if(!in_array($this->_file_ext, $this->_allowed)){
			$this->_allowed_ext = implode(', ', $this->_allowed);
			$this->addError('Invalid file type! Allowed: <br>'.$this->_allowed_ext);
		}
		
		if(empty($this->_errors)){
			$this->_passed = true;
		}

		return $this;
	}

	public function store($path){
		$this->_file_path = $path.substr(md5(time()), 0, 10).'.'.$this->_file_ext;
		move_uploaded_file($this->_file_temp, $this->_file_path);
	}

	public function addError($error){
		$this->_errors[] = $error; 
	}

	public function errors(){
		return $this->_errors;
	}

	public function getFilePath(){
		return $this->_file_path;
	}

	public function passed(){
		return $this->_passed;
	}

}