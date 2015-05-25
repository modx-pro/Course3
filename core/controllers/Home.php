<?php

class Controllers_Home {
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
		return "Мы выводим страницу <b>Home<b>";
	}

}