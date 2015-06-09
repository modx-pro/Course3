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
		// $manager = $this->core->xpdo->getManager();
		// $manager->createObjectContainer('Brevis\Model\News');

		$content = '';
		$news = $this->core->xpdo->newObject('Brevis\Model\News');
		$news->fromArray(array(
			'pagetitle' => 'Новость 1',
			'alias' => 'news1'
		));
		$news->save();
		$content .= '<pre>' . print_r($news->toArray(), true) . '</pre>';

		$news = $this->core->xpdo->getObject('Brevis\Model\News', array('alias' => 'news1'));
		$news->set('longtitle', rand());
		$news->save();
		$content .= '<pre>' . print_r($news->toArray(), true) . '</pre>';

		$news->remove();

		return $this->template('test', array(
			'title' => 'Тестовая страница',
			'pagetitle' => 'Тестовая страница',
			'content' => $content,
		), $this);
	}

}