<?php

class Controller {
	/** @var Core $core */
	public $core;


	/**
	 * Конструктор класса, требует передачи Core
	 *
	 * @param Core $core
	 */
	function _construct(Core $core) {
		$this->core = $core;
	}


	/**
	 * Основной рабочий метод
	 *
	 * @return string
	 */
	public function run() {
		return "Hello World!";
	}

}