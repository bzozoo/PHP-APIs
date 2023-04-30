<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Config
require_once 'config.php';

//Model
class DB {
	private $dbconfig;

	public function __construct($dbconfig) {
		$this->dbconfig = $dbconfig;
	}

	public function CONN() {
		try {
			return new PDO('mysql:host=' . $this->dbconfig['host'] . ";dbname=" . $this->dbconfig['dbname'], $this->dbconfig['user'], $this->dbconfig['pass']);
		} catch (PDOException $e) {
			return false;
		}
	}

	function queryAllFromTable($tablename) {
		$sth = $this->CONN();
		if ($sth) {
			try {
				$sql = "SELECT NODEHOST FROM " . $tablename;
				$sth = $sth->query($sql);
				return $sth->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				return ['message' => $e];
			}

		}
		return ['message' => "Database connection error"];
	}

	function insertAllToTable($tablename, $keys, $values) {
		$dbh = $this->CONN();
		if ($dbh) {

			$placeholders = array_fill(0, count($keys), "?");
			$sql = "INSERT INTO " . $tablename . " (" . implode(", ", $keys) . ") VALUES ";
			$sql .= "(" . implode(", ", $placeholders) . ")";

			$stmt = $dbh->prepare($sql);
			foreach ($values as $value) {
				$data = array_values($value);
				if (count($data) === count($keys)) {
					$stmt->execute($data);
				}
			}
		}
	}
}

$users = array(
	array('Name' => 'John', 'Email' => 'john@example1.com', 'Age' => 25),
	array('Name' => 'Jane', 'Email' => 'jane@example2.com', 'Age' => 30),
	array('Name' => 'Bob', 'Email' => 'bob@example3.com', 'Age' => 40),
	array('Name' => 'Alice', 'Email' => 'alice@example4.com', 'Age' => 35),
	array('Name' => 'Tom', 'Email' => 'tom@example5.com', 'Age' => 27),
);

//CONTROLLER
$db = new DB(DBCONFIG());
$db->insertAllToTable("TestUser", array('Name', 'Email', 'Age'), $users);