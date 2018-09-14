<?php
namespace datalayer\mysql;

class TransactionsDataLayer  extends \datalayer\database\TransactionsDataLayer {
	private $db;

	public function __construct($db) {
		parent::__construct($db);
		$this->db = $db;
	}

	function getTransactions($year, $month, $sortBy, $sortOrder) {
		$bindParams = array();

		$sql = "SELECT 
		entry.*, COALESCE(category.name, entry.category) AS category_name 
		FROM entry LEFT JOIN category ON entry.category = category.key WHERE 1=1";

		if (!empty($month) && is_numeric($month)) {
			$sql .= " AND EXTRACT(MONTH FROM value_date) = :month";
			$bindParams[':month'] = $month;
		}

		if (!empty($year) && is_numeric($year)) {
			$sql .= " AND EXTRACT(YEAR FROM value_date) = :year";
			$bindParams[':year'] = $year;
		}

		$sql .= " ORDER BY " . $sortBy . ' ' . $sortOrder;

		$statement = $this->db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
		$statement->execute($bindParams);

		return $statement->fetchAll();
	}

}
