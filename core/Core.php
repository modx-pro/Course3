<?php

namespace Brevis;

use \Fenom as Fenom;
use \Exception as Exception;

class Core {
	public $config = array();
	/** @var Fenom $fenom */
	public $fenom;


	/**
	 * Конструктор класса
	 *
	 * @param array $config
	 */
	function __construct(array $config = array()) {
		$this->config = array_merge(
			array(
				'templatesPath' => dirname(__FILE__) . '/Templates/',
				'cachePath' => dirname(__FILE__) . '/Cache/',
				'fenomOptions' => array(
					'auto_reload' => true,
					'force_verify' => true,
				),
			),
			$config
		);
	}


	/**
	 * Обработка входящего запроса
	 *
	 * @param $uri
	 */
	public function handleRequest($uri) {
		$request = explode('/', $uri);

		$className = '\Brevis\Controllers\\' . ucfirst(array_shift($request));
		/** @var Controller $controller */
		if (!class_exists($className)) {
			$controller = new Controllers\Home($this);
		}
		else {
			$controller = new $className($this);
		}

		$initialize = $controller->initialize($request);
		if ($initialize === true) {
			$response = $controller->run();
		}
		elseif (is_string($initialize)) {
			$response = $initialize;
		}
		else {
			$response = 'Возникла неведомая ошибка при загрузке страницы';
		}

		echo $response;
	}


	/**
	 * Получение экземпляра класса Fenom
	 *
	 * @return bool|Fenom
	 */
	public function getFenom() {
		if (!$this->fenom) {
			try {
				if (!file_exists($this->config['cachePath'])) {
					mkdir($this->config['cachePath']);
				}
				$this->fenom = Fenom::factory($this->config['templatesPath'], $this->config['cachePath'], $this->config['fenomOptions']);
			}
			catch (Exception $e) {
				$this->log($e->getMessage());
				return false;
			}
		}

		return $this->fenom;
	}


	/**
	 * Метод удаления директории с кэшем
	 *
	 */
	public function clearCache() {
		Core::rmDir($this->config['cachePath']);
		mkdir($this->config['cachePath']);
	}


	/**
	 * Логирование. Пока просто выводит ошибку на экран.
	 *
	 * @param $message
	 * @param $level
	 */
	public function log($message, $level = E_USER_ERROR) {
		if (!is_scalar($message)) {
			$message = print_r($message, true);
		}
		trigger_error($message, $level);
	}


	/**
	 * Удаление ненужных файлов в пакетах, установленных через Composer
	 *
	 * @param mixed $base
	 */
	public static function cleanPackages($base = '') {
		if (!is_string($base)) {
			$base = dirname(dirname(__FILE__)) . '/vendor/';
		}
		if ($dirs = @scandir($base)) {
			foreach ($dirs as $dir) {
				if (in_array($dir, array('.', '..'))) {
					continue;
				}
				$path = $base . $dir;
				if (is_dir($path)) {
					if (in_array($dir, array('tests', 'test', 'docs', 'gui', 'sandbox', 'examples', '.git'))) {
						Core::rmDir($path);
					}
					else {
						Core::cleanPackages($path . '/');
					}
				}
				elseif (pathinfo($path, PATHINFO_EXTENSION) != 'php') {
					unlink($path);
				}
			}
		}
	}


	/**
	 * Рекурсивное удаление директорий
	 *
	 * @param $dir
	 */
	public static function rmDir($dir) {
		$dir = rtrim($dir, '/');
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != '.' && $object != '..') {
					if (is_dir($dir . '/' . $object)) {
						Core::rmDir($dir . '/' . $object);
					}
					else {
						unlink($dir . '/' . $object);
					}
				}
			}
			rmdir($dir);
		}
	}

}