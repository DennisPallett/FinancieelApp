<?php
namespace datalayer\database;

abstract class TransactionsDataLayer implements \datalayer\ITransactionsDataLayer {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	function updateCategory($transactionId, $category) {
		if (empty($transactionId))
			return false;

		if (empty($category))
			$category = null;

		$sql = "UPDATE entry SET category = :category WHERE id = :id";
		$statement = $this->db->prepare($sql);

		$statement->execute(array(
			':id' => $transactionId,
			':category' => $category
		));

		return true;
	}

	function getTransactions($year, $month, $sortBy, $sortOrder) {
		$bindParams = array();

		$sql = "SELECT 
		entry.*, COALESCE(category.name, entry.category) AS category_name 
		FROM entry LEFT JOIN category ON entry.category = category.key WHERE 1=1";

		if (!empty($month) && is_numeric($month)) {
			$sql .= " AND date_part('month', value_date) = :month";
			$bindParams[':month'] = $month;
		}

		if (!empty($year) && is_numeric($year)) {
			$sql .= " AND date_part('year', value_date) = :year";
			$bindParams[':year'] = $year;
		}

		$sql .= " ORDER BY " . $sortBy . ' ' . $sortOrder;

		$statement = $this->db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
		$statement->execute($bindParams);

		return $statement->fetchAll();
	}

}
