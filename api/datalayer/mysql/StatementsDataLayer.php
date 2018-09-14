<?php
namespace datalayer\mysql;

class StatementsDataLayer extends \datalayer\database\StatementsDataLayer {
	private $db;

	public function __construct($db) {
		parent::__construct($db);
		$this->db = $db;
	}

	public function getStatements ($year, $month, $sortBy, $sortOrder) {
		$bindParams = array();

		$sql = "SELECT 
			statement.*,
			(SELECT COUNT(*) FROM entry WHERE statement_id = statement.id) AS entry_count
		FROM statement
		WHERE 1=1
		";

		if (!empty($month) && is_numeric($month)) {
			$sql .= " AND EXTRACT(MONTH FROM start_balance_date) = :month";
			$bindParams[':month'] = $month;
		}

		if (!empty($year) && is_numeric($year)) {
			$sql .= " AND EXTRACT(YEAR FROM start_balance_date) = :year";
			$bindParams[':year'] = $year;
		}

		$sql .= " ORDER BY " . $sortBy . ' ' . $sortOrder;
		
		$statement = $this->db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
		$statement->execute($bindParams);

		return $statement->fetchAll();
	}
	
}