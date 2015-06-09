<?php

define('PROJECT_API_MODE', true);
use \xPDO\xPDO as xPDO;

$base = dirname(dirname(dirname(__FILE__))) . '/';
require_once $base . 'index.php';
$xPDO = $Core->xpdo;
$xml = dirname(__FILE__) . '/' . PROJECT_NAME_LOWER . '.schema.xml';

/** @var \xPDO\Om\xPDOManager $manager */
$manager = $xPDO->getManager();
/** @var \xPDO\Om\xPDOGenerator $generator */
$generator = $manager->getGenerator();

$generator->parseSchema($xml, PROJECT_MODEL_PATH);
$xPDO->log(xPDO::LOG_LEVEL_INFO, 'Модель сгенерирована');

$files = scandir(PROJECT_MODEL_PATH . PROJECT_NAME . '/Model/');
foreach ($files as $file) {
	$src = PROJECT_MODEL_PATH . PROJECT_NAME . '/Model/' . $file;
	$dst = PROJECT_MODEL_PATH . $file;
	if ($file == 'metadata.mysql.php') {
		@unlink($dst);
		rename($src, $dst);
	}
	elseif (!file_exists($dst)) {
		rename($src, $dst);
	}
}
$Core::rmDir(PROJECT_MODEL_PATH . 'mysql');
rename(PROJECT_MODEL_PATH . PROJECT_NAME . '/Model/mysql', PROJECT_MODEL_PATH . 'mysql');
$Core::rmDir(PROJECT_MODEL_PATH . PROJECT_NAME);
$xPDO->log(xPDO::LOG_LEVEL_INFO, 'Файлы перенесены');