<?php

namespace Brevis;

use \Fenom as Fenom;
use \xPDO\xPDO as xPDO;
use \Exception as Exception;
use \Parsedown as Parsedown;

class Core {
	public $config = array();
	/** @var Fenom $fenom */
	public $fenom;
	/** @var xPDO $xpdo */
	public $xpdo;
	/** @var Parsedown $parser */
	public $parser;


	/**
	 * Конструктор класса
	 *
	 * @param string $config Имя файла с конфигом
	 */
	function __construct($config = 'config') {
		if (is_string($config)) {
			$config = dirname(__FILE__) . "/Config/{$config}.inc.php";
			if (file_exists($config)) {
				require $config;
				/** @var string $database_dsn */
				/** @var string $database_user */
				/** @var string $database_password */
				/** @var array $database_options */
				try {
					$this->xpdo = new xPDO($database_dsn, $database_user, $database_password, $database_options);
					$this->xpdo->setPackage('Model', PROJECT_CORE_PATH);
					$this->xpdo->startTime = microtime(true);
				}
				catch (Exception $e) {
					exit($e->getMessage());
				}
			}
			else {
				exit('Не могу загрузить файл конфигурации');
			}
		}
		else {
			exit('Неправильное имя файла конфигурации');
		}

		$this->xpdo->setLogLevel(defined('PROJECT_LOG_LEVEL') ? PROJECT_LOG_LEVEL : xPDO::LOG_LEVEL_ERROR);
		$this->xpdo->setLogTarget(defined('PROJECT_LOG_TARGET') ? PROJECT_LOG_TARGET : 'FILE');
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
				if (!file_exists(PROJECT_CACHE_PATH)) {
					mkdir(PROJECT_CACHE_PATH);
				}
				$this->fenom = Fenom::factory(PROJECT_TEMPLATES_PATH, PROJECT_CACHE_PATH, PROJECT_FENOM_OPTIONS);
			}
			catch (Exception $e) {
				$this->log($e->getMessage());
				return false;
			}
		}

		return $this->fenom;
	}


	/**
	 * Получение парсера текстов
	 *
	 * @return Parsedown
	 */
	public function getParser() {
		if (!$this->parser) {
			$this->parser = new Parsedown();
		}

		return $this->parser;
	}


	/**
	 * Метод удаления директории с кэшем
	 *
	 */
	public function clearCache() {
		Core::rmDir(PROJECT_CACHE_PATH);
		mkdir(PROJECT_CACHE_PATH);
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