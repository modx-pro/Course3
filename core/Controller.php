<?php

class Controller {
	/** @var Core $core */
	public $core;


	/**
	 * Конструктор класса, требует передачи Core
	 *
	 * @param Core $core
	 */
	function __construct(Core $core) {
		$this->core = $core;
	}


	/**
	 * @param array $params
	 *
	 * @return bool
	 */
	public function initialize(array $params = array()) {

		return true;
	}


	/**
	 * Основной рабочий метод
	 *
	 * @return string
	 */
	public function run() {
		return "Hello World!";
	}


	/**
	 * @param string $url
	 */
	public function redirect($url = '/') {
		header("Location: {$url}");
		exit();
	}

}