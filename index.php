<?php

ini_set('display_errors', 1);
ini_set('error_reporting', -1);

if (!class_exists('Core')) {
	require_once 'core/Core.php';
}
$Core = new Core();

$req = !empty($_REQUEST['q'])
	? trim($_REQUEST['q'])
	: '';
$Core->handleRequest($req);