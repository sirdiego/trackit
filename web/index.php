<?php
use Slim\Slim;
use Slim\Views\Twig;

define('trackit_root', realpath('..') . DIRECTORY_SEPARATOR);

require_once trackit_root . 'vendor/autoload.php';

$view = new Twig();

$config = include trackit_root . 'config/config.php';
$config = array_merge($config, [
	'view' => $view,
]);

$app = new Slim($config);
$app->get('/', function() use ($app) {
	echo $app->view->render('index.twig', ['name' => 'trackit']);
});
$app->run();

