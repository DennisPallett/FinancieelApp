<?php
namespace datalayer\mysql;

class ReportingDataLayer extends \datalayer\database\ReportingDataLayer {
	
	private $db;

	public function __construct($db) {
		parent::__construct($db);
		$this->db = $db;
	}

	public function getAmountsByCategory () {
		$records = $this->db->query("
			SELECT 
				EXTRACT(YEAR FROM value_date) as `year`,
				EXTRACT(MONTH FROM value_date) AS month,
                (CASE WHEN(amount > 0) THEN 'inkomen' ELSE 'lasten' END) AS stack,
				sum(amount) AS total_amount
			FROM entry
			GROUP BY 
				year,
				month,
                stack
			ORDER BY
				year ASC,
                month ASC
		");

		return $records;
	}
}