<?php

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
				'controllersPath' => dirname(__FILE__) . '/Controllers/',
				'templatesPath' => dirname(__FILE__) . '/Templates/',
				'cachePath' => dirname(__FILE__) . '/Cache/',
				'fenomOptions' => array(
					'auto_reload' => true,
					'force_verify' => true,
				),
			),
			$config
		);
		require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
	}


	/**
	 * Обработка входящего запроса
	 *
	 * @param $uri
	 */
	public function handleRequest($uri) {
		// Определяем страницу для вывода
		$request = explode('/', $uri);
		// Имена контроллеров у нас с большой буквы
		$name = ucfirst(array_shift($request));
		// Полный путь до запрошенного контроллера
		$file = $this->config['controllersPath'] . $name . '.php';
		// Если нужного контроллера нет, то используем контроллер Home
		if (!file_exists($file)) {
			$file = $this->config['controllersPath'] . 'Home.php';
			// Определяем имя класса, согласно принятым у нас правилам
			$class = 'Controllers_Home';
		}
		else {
			$class = 'Controllers_' . $name;
		}
		// Если контроллер еще не был загружен - загружаем его
		if (!class_exists($class)) {
			require_once $file;
		}
		// И запускаем
		/** @var Controllers_Home|Controllers_Test $controller */
		$controller = new $class($this);
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
		$this->rmDir($this->config['cachePath']);
		mkdir($this->config['cachePath']);
	}


	/**
	 * Рекурсивное удаление директорий
	 *
	 * @param $dir
	 */
	public function rmDir($dir) {
		$dir = rtrim($dir, '/');
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != '.' && $object != '..') {
					if (is_dir($dir . '/' . $object)) {
						$this->rmDir($dir . '/' . $object);
					}
					else {
						unlink($dir . '/' . $object);
					}
				}
			}
			rmdir($dir);
		}
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

}