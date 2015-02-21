<?php
namespace Diego\Trackit\Model;

class Moment {

	public $identifier;

	public $parent;

	public $value;

	public $flags;

	protected $new = FALSE;

	public function __construct() {
		$this->new = TRUE;
	}

	public function isStart() {
		return (bool) $this->flags & 1;
	}

	static public function findByIdentifier($identifier, \PDO $database) {
		$stmt = $database->prepare('SELECT * FROM `moment` WHERE `identifier` = :identifier');
		$stmt->execute([':identifier' => $identifier]);
		$moment = $stmt->fetchObject(get_class());
		return $moment;
	}

	static public function findByParent($identifier, \PDO $database) {
		$stmt = $database->prepare('SELECT * FROM `moment` WHERE `parent` = :identifier');
		$stmt->execute([':identifier' => $identifier]);
		$moments = $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
		return $moments;
	}

	static public function persist(\PDO $database) {
		$parameters = [];

		if($this->uuid) {
			$parameters['uuid'] = $this->uuid;
		}else{
			$parameters['uuid'] = 'NOW()';
		}
		if($this->parent) {
			$parameters['parent'] = $this->parameter;
		}else{
			$parameters['parent'] = 'NULL';
		}
		if($this->value) {
			$parameters['value'] = $this->value;
		}else{
			$parameters['value'] = 'NOW()';
		}
		if($this->flags) {
			$parameters['flags'] = $this->flags;
		}else{
			$parameters['flags'] = 'b\'0\'';
		}
	}
}

