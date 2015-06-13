<?php

namespace Brevis\Controllers;

use Brevis\Controller as Controller;

class News extends Controller {
	public $name = 'news';
	/** @var \Brevis\Model\News $item */
	public $item = null;
	public $limit = 10;

	/**
	 * @param array $params
	 *
	 * @return bool
	 */
	public function initialize(array $params = array()) {
		if (empty($params)) {
			$this->redirect("/{$this->name}/");
		}
		elseif (!empty($params[0])) {
			$c = $this->core->xpdo->newQuery('Brevis\Model\News');
			if (is_numeric($params[0])) {
				$c->where(array('id' => $params[0]));
			}
			else {
				$c->where(array('alias' => $params[0]));
			}
			if ($news = $this->core->xpdo->getObject('Brevis\Model\News', $c)) {
				$alias = $news->get('alias');
				if (isset($params[1]) || $params[0] != $alias) {
					$this->redirect("/{$this->name}/{$alias}");
				}
				else {
					$this->item = $news;
				}
			}
			else {
				$this->redirect("/{$this->name}/");
			}
		}

		return true;
	}


	/**
	 * @return string
	 */
	public function run() {
		if ($this->item) {
			$data = array(
				'title' => $this->item->get('pagetitle'),
				'pagetitle' => $this->item->get('pagetitle'),
				'longtitle' => $this->item->get('longtitle'),
				'content' => $this->core->getParser()->text($this->item->get('text')),
			);
		}
		else {
			$data = array(
				'title' => 'Новости',
				'pagetitle' => 'Новости',
				'items' => $this->getItems(),
				'content' => '',
			);
		}

		return $this->template('news', $data, $this);
	}


	/**
	 * Выбор последних новостей с обрезкой текста
	 *
	 * @return array
	 */
	public function getItems() {
		$rows = array();
		$c = $this->core->xpdo->newQuery('Brevis\Model\News');
		$c->select($this->core->xpdo->getSelectColumns('Brevis\Model\News', 'News'));
		$c->sortby('id', 'DESC');
		$c->limit($this->limit);
		if ($c->prepare() && $c->stmt->execute()) {
			while ($row = $c->stmt->fetch(\PDO::FETCH_ASSOC)) {
				$cut = strpos($row['text'], "\n");
				if ($cut !== false) {
					$row['text'] = substr($row['text'], 0, $cut);
					$row['cut'] = true;
				}
				else {
					$row['cut'] = false;
				}
				$row['text'] = $this->core->getParser()->text($row['text']);
				$rows[] = $row;
			}
		}
		else {
			$this->core->log('Не могу выбрать новости:' . print_r($c->stmt->errorInfo(), true));
		}

		return $rows;
	}

}