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

	public function moment($identifier) {
		$moment = Model\Moment::findByIdentifier($identifier, $this->app->database);	
		$children = Model\Moment::findByParent($identifier, $this->app->database);

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
	
	public function stop($parent) {
		try {
			$moment = Model\Moment::create();
			$moment->flags = Model\Moment::FLAG_STOP;
			$moment->parent = $parent;
			Model\Moment::persist($moment, $this->app->database);
			$this->app->redirect('/moment/', $parent);
		} catch (\PDOException $e) {
			$this->app->flash('error', 'Sorry, something went wrong ('.$e->getMessage().')!');
			$this->app->redirect('/');
		}
	}
	public function start() {
		try {
			$moment = Model\Moment::create();
			$moment->flags = Model\Moment::FLAG_START;
			Model\Moment::persist($moment, $this->app->database);
			$this->app->redirect('/moment/' . $moment->identifier);
		} catch (\PDOException $e) {
			$this->app->flash('error', 'Sorry, something went wrong ('.$e->getMessage().')!');
			$this->app->redirect('/');
		}
	}
}

