<?php

use Slim\Slim;

require_once '../vendor/autoload.php';

$app = new Slim();
$app->get('/', function() {
	echo "Hello trackit!";
});
$app->run();
