<?php

ini_set('display_errors', 1);
ini_set('error_reporting', -1);

// Массив доступных страниц
$pages = array('home', 'test');
// Определяем страницу для вывода
$page = '';
// Если запрос не пуст - проверяем, есть ли он в массиве наших страниц
if (!empty($_REQUEST['q'])) {
	$request = explode('/', $_REQUEST['q']);
	// Если есть - окей, всё верно, используем это имя
	if (in_array(strtolower($request[0]), $pages)) {
		$page = strtolower($request[0]);
	}
}
// Иначе используем страницу по умолчанию
if (empty($page)) {
	$page = 'home';
}

echo "Мы выводим страницу <b>{$page}<b>";