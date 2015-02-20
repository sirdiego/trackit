<?php
use Slim\Slim;
use Slim\Views\Twig;
use Diego\Trackit as Trackit;

define('trackit_root', realpath('..') . DIRECTORY_SEPARATOR);

require_once trackit_root . 'vendor/autoload.php';

$view = new Twig();
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

$config = include trackit_root . 'config/config.php';
$config = array_merge($config, [
	'view' => $view,
]);

$app = new Slim($config);

$app->container->singleton('database', function () use ($app) {
	return new \PDO($app->config('database.dsn'), $app->config('database.user'), $app->config('database.password'));
});

$app->get('/', function () use ($app) {
	$controller = new Trackit\Controller\Index($app);
	$controller->index();
});

$app->get('/moment/:moment', function ($moment) use ($app) {
	$controller = new Trackit\Controller\Index($app);
	$controller->moment($moment);
})->name('moment');

$app->run();

