<?php
namespace classes;

class varArray implements \Iterator {
	private $values = array();
	private $types = array();
	private $position;

	public function  __construct(array $values) {
		foreach ($values as $key => $value) {
			$this->set($key, $value);
		}
	}

	public function set($name, $value) {
		if (is_array($value)) {
			$this->types[$name] = 'array';
		} else {
			$this->types[$name] = 'string';
		}

		$this->values[$name] = $value;
	}

	public function getData() {
		return $this->values;
	}

	public function is_set($name) {
		if (stristr($name, '/')) {
			$one = str_bis($name, '/');
			$two = str_nach($name, '/');
			if (!isset($this->values[$one])) {
				return FALSE;
			}

			return $this->isset($two);
		} else {
			return isset($this->values[$name]);
		}
	}

	public function get($name, $default = NULL) {
		if (stristr($name, '/')) {
			$one = str_bis($name, '/');
			$two = str_nach($name, '/');
			if (!method_exists($this->get($one), 'get')) {
				return '';
			}

			return $this->get($one)->get($two);
		} else {
			if (isset($this->values[$name])) {
				if ($this->types[$name] == 'array') {
					return new \classes\varArray($this->values[$name]);
				}

				return $this->values[$name];
			} else {
				if ($default === NULL) {
					return new \classes\varArray(array());
				} else {
					return $default;
				}
			}
		}
	}

	public function getInt($name, $default = NULL) {
		$res = $this->get($name, $default);

		return (int)$res;
	}

	function __toString() {
		return '';
	}

	public function rewind() {
		$this->position = 0;
	}

	public function count() {
		return count($this->values);
	}

	public function valid() {
		if (!is_array($this->values)) {
			$this->values = array();
		}

		return $this->position < count($this->values);
	}

	private function keyAtPos($position) {
		$K = array_keys($this->values);

		return $K[$position];
	}

	public function key() {
		return $this->keyAtPos($this->position);
	}

	public function current() {
		return $this->get($this->keyAtPos($this->position), '');
	}

	public function next() {
		$this->position++;
	}
}

?>