<?php

class Core {
	public $config = array();


	/**
	 * Конструктор класса
	 *
	 * @param array $config
	 */
	function __construct(array $config = array()) {
		$this->config = array_merge(
			array(), $config
		);
	}


	/**
	 * Обработка входящего запроса
	 *
	 * @param $uri
	 */
	public function handleRequest($uri) {
		// Массив доступных страниц
		$pages = array('home', 'test');
		// Определяем страницу для вывода
		$page = '';
		$request = explode('/', $uri);
		// Если есть - окей, всё верно, используем это имя
		if (in_array(strtolower($request[0]), $pages)) {
			$page = strtolower($request[0]);
		}
		// Иначе используем страницу по умолчанию
		if (empty($page)) {
			$page = 'home';
		}

		echo "Мы выводим страницу <b>{$page}<b>";
	}

}