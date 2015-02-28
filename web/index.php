<?php
use Slim\Slim;
use Slim\Views\Twig;
use Diego\Trackit as Trackit;

define('TRACKIT_ROOT', realpath('..') . DIRECTORY_SEPARATOR);

require_once TRACKIT_ROOT . 'vendor/autoload.php';

session_start();

$view = new Twig();
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

$config = include TRACKIT_ROOT . 'config/config.php';
$config = array_merge($config, [
	'view' => $view,
]);

$app = new Slim($config);

$app->container->singleton('database', function () use ($app) {
	return new \PDO($app->config('database.dsn'), $app->config('database.user'), $app->config('database.password'));
});

$app->get('/', function () use ($app) {
	$controller = new Trackit\Controller\StopWatch($app);
	$controller->index();
});

$app->get('/moment/:moment', function ($moment) use ($app) {
	$controller = new Trackit\Controller\StopWatch($app);
	$controller->moment($moment);
})->name('moment');


$app->get('/moment/:moment/add', function ($moment) use ($app) {
	$controller = new Trackit\Controller\StopWatch($app);
	$controller->add($moment);
})->name('addMoment');

$app->get('/start/', function () use ($app) {
	$controller = new Trackit\Controller\StopWatch($app);
	$controller->start();
})->name('start');

$app->get('/stop/:parent', function ($parent) use ($app) {
	$controller = new Trackit\Controller\StopWatch($app);
	$controller->stop($parent);
})->name('stop');

$app->run();

