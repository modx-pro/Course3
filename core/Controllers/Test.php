<?php

if (!class_exists('Controller')) {
	require_once dirname(dirname(__FILE__)) . '/Controller.php';
}

class Controllers_Test extends Controller {
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