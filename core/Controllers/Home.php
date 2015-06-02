<?php

if (!class_exists('Controller')) {
	require_once dirname(dirname(__FILE__)) . '/Controller.php';
}

class Controllers_Home extends Controller {

	/**
	 * @param array $params
	 *
	 * @return bool
	 */
	public function initialize(array $params = array()) {
		if (!empty($_REQUEST['q'])) {
			$this->redirect('/');
		}
		return true;
	}


	/**
	 * @return string
	 */
	public function run() {
		// Метод getFenom() может вернуть или false, или объект
		// Так что нужно проверять, что именно приходит
		if ($fenom = $this->core->getFenom()) {
			return $fenom->fetch('home.tpl');
		}
		else {
			return '';
		}
	}

}