<?php
namespace Diego\Trackit\Model;

use \J20\Uuid\Uuid;

class Moment {

	const FLAG_START = 1;
	const FLAG_STOP = 2;

	public $identifier;

	public $parent;

	public $value;

	public $flags;

	public $new = false;

	public function isStart() {
		return (bool)$this->isFlagSet(self::FLAG_START);
	}

	public function isStop() {
		return (bool)$this->isFlagSet(self::FLAG_STOP);
	}

	protected function isFlagSet($flag) {
		return $this->flags & $flag;
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

	static public function create() {
		$moment = new Moment();
		$moment->identifier = Uuid::v4();
		$date = new \DateTime();
		$moment->value = $date->format('Y-m-d H:i:s');
		$moment->new = true;
		$moment->flags = 0;

		return $moment;
	}

	static public function persist(Moment $moment, \PDO $database) {
		if($moment->new) {
			$stmt = $database->prepare('INSERT INTO `moment` VALUES(:identifier, :parent, :value, :flags)');
			$stmt->bindValue(':identifier', $moment->identifier);
			$stmt->bindValue(':parent', $moment->parent);
			$stmt->bindValue(':value', $moment->value);
			$stmt->bindValue(':flags', $moment->flags, \PDO::PARAM_INT);
			$result = $stmt->execute();
			if(!$result) {
				$error = $stmt->errorInfo();
				throw new \PDOException($error[2], $error[1]);
			}
		} else {
			throw new \Exception('Changing existing moments is not implemented yet.');
		}
	}
}

