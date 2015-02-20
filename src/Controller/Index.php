<?php
namespace Diego\Trackit\Controller;

use \Slim\Slim;

class Index {
	protected $app;

	public function __construct(Slim $app) {
		$this->app = $app;
	}

	public function index() {
		$stmt = $this->app->database->query('SELECT * FROM `moment`');
		$moments = $stmt->fetchAll();
		$assignments = [
			'name' => 'trackit controller',
			'moments' => $moments
		];
		echo $this->app->view->render('index.twig', $assignments);
	}

	public function moment($moment) {
		$stmt = $this->app->database->prepare('SELECT * FROM `moment` WHERE `identifier` = :identifier');
		$stmt->execute([':identifier' => $moment]);
		$moment = $stmt->fetchObject();
		echo $this->app->view->render('moment.twig', ['moment' => $moment]);
	}
}

