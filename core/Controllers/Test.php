<?php

namespace Brevis\Controllers;

use Brevis\Controller as Controller;

class Test extends Controller {
	public $name = 'test';

	/**
	 * @param array $params
	 *
	 * @return bool
	 */
	public function initialize(array $params = array()) {
		if (empty($params)) {
			$this->redirect('/test/');
		}
		return true;
	}


	/**
	 * @return string
	 */
	public function run() {
		return $this->template('test', array(
			'title' => 'Тестовая страница',
			'pagetitle' => 'Тестовая страница',
			'content' => 'Текст тестовой страницы',
		), $this);
	}

}