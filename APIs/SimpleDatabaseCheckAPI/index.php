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
}

//CONTROLLER
$db = new DB(DBCONFIG());
$result = $db->queryAllFromTable('NODEHOST');
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';

$data = array(
	'message' => 'Nodetest service is active',
	'cors' => $origin,
	'date' => date('Y/m/d H:i:s'),
	'testvalue' => 'OK1',
	'databaseresult' => $result,
);

//View
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: " . $origin);

echo json_encode($data, JSON_PRETTY_PRINT);
