<?php
namespace image;

class image_obj {
	private $fw;
	private $data = array();
	public function  __construct($fw) {
		$this->fw = $fw;
	}
	public function fill($data) {
		$this->data = $data;
	}
	public function get($name) {
		if(isset($this->data[$name])) return $this->data[$name];
		else return '';
	}
}

class image_access implements \Iterator {
	private $fw;

	private $position;
	private $datasets = false;

	public function  __construct($fw) {
		$this->fw = $fw;
	}
	public function get() {
		$Q = "SELECT * FROM ... ";
		$datasets = $this->fw->DC->getAllByQuery($Q);
		$list = array();
		for($i=0;$i<count($datasets);$i++) {
			$one = new image_obj($this->fw);
			$one->fill($datasets[$i]);
			$list[] = $one;
		}
		return $list;
	}

	public function rewind() {
		$this->position = 0;
		if($this->datasets==false) $this->datasets = $this->get();
	}
	public function valid()   { return $this->position < count($this->datasets); }
	public function key()     { return $this->position; }
	public function current() { return $this->datasets[$this->position]; }
	public function next()    { $this->position++; }

}

class image {
	public $fw;
	public $id;
	public $params = array();
	public function __construct($fw, $params=array()) {
		// {{{
		$this->params = $params;
		$this->fw = $fw;
		// }}}
	}

}
?>