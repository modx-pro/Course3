<?php

namespace Brevis\Controllers;

use Brevis\Controller as Controller;

class News extends Controller {
	public $name = 'news';
	/** @var \Brevis\Model\News $item */
	public $item = null;
	public $limit = 2;
	public $page = 1;
	private $_offset = 0;
	private $_total = 0;

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
			if (is_numeric($params[0]) && $params[0] > 1) {
				if (!isset($params[1]) || !empty($params[1])) {
					$this->redirect("/{$this->name}/$params[0]/");
				}
				$this->page = (int)$params[0];
				$this->_offset = ($this->page - 1) * $this->limit;
			}
			else {
				$c = $this->core->xpdo->newQuery('Brevis\Model\News', array('alias' => $params[0]));
				if ($news = $this->core->xpdo->getObject('Brevis\Model\News', $c)) {
					$this->item = $news;
				}
			}

			if (!$this->_offset && !$this->item) {
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
				'pagination' => $this->getPagination($this->_total, $this->page, $this->limit),
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
		$this->_total = $this->core->xpdo->getCount('Brevis\Model\News');
		if ($this->_offset >= $this->_total) {
			$this->redirect("/{$this->name}/");
		}
		$c->select($this->core->xpdo->getSelectColumns('Brevis\Model\News', 'News'));
		$c->sortby('id', 'DESC');
		$c->limit($this->limit, $this->_offset);
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