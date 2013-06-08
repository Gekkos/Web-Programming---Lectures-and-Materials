<?php
	class Technologies {
		private $data = array();

		public function addTechnology($item) {
			$this -> data[] = $item;
		}

		public function getAll() {
			return $this -> data;
		}
	}

	$tech = new Technologies();

	$fileContents = file_get_contents("technologies.txt");
	foreach(explode(PHP_EOL, $fileContents) as $line) {
		$tech -> addTechnology($line);
	}


	echo json_encode ($tech -> getAll());