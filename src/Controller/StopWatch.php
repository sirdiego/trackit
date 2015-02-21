<?php
namespace Diego\Trackit\Controller;

use \Slim\Slim;
use \Diego\Trackit\Model;

class StopWatch {
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
		$moment = Model\Moment::findByIdentifier($moment, $this->app->database);	

		$stmt = $this->app->database->prepare('SELECT * FROM `moment` WHERE `parent`= :identifier');
		$stmt->execute([':identifier' => $moment->identifier]);
		$children = $stmt->fetchAll();

		$assignments = [
			'moment' => $moment,
			'children' => $children
		];
		echo $this->app->view->render('moment.twig', $assignments);
	}
	
	public function add($parent) {
		try {
			$stmt = $this->app->database->prepare('INSERT INTO `moment` VALUES (UUID(), :parent, NOW(), 0)');
			if(!$stmt->execute([':parent' => $parent])) {
				throw new \PDOException();
			}
		} catch (\PDOException $e) {
			$this->app->flash('error', 'Sorry, something went wrong!');
		}
		$this->app->redirect('/moment/' . $parent);
	}
}

