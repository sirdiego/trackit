<?php
namespace Diego\Trackit\Model;

class Moment {

	public $identifier;

	public $parent;

	public $value;

	public $flags;

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
		$moment = $stmt->fetchObject(get_class());
		return $moment;
	}
}
