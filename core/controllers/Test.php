<?php

if (!class_exists('Controller')) {
	require_once dirname(dirname(__FILE__)) . '/Controller.php';
}

class Controllers_Test extends Controller {

	/**
	 * @return string
	 */
	public function run() {
		return "Мы выводим страницу <b>Test<b>";
	}

}