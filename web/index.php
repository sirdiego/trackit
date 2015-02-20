<?php

use Slim\Slim;
use Slim\Views\Twig;

require_once '../vendor/autoload.php';

$view = new Twig();

$config = [
	'view' => $view,
	'templates.path' => '../templates/'
];

$app = new Slim($config);
$app->get('/', function() use ($app) {
	echo $app->view->render('index.twig', ['name' => 'trackit']);
});
$app->run();

