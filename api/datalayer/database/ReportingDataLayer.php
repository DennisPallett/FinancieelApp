<?php
namespace datalayer\database;

abstract class ReportingDataLayer implements \datalayer\IReportingDataLayer {
	
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function getBalances () {
		return $records = $this->db->query("
			SELECT 
				start_balance_date, start_balance_amount,
				end_balance_date, end_balance_amount
			FROM statement
			ORDER BY start_balance_date ASC
		");
	}

	public function getAmountsByCategory () {
		$records = $this->db->query("
			SELECT 
				date_part('year', value_date) as year,
				date_part('month', value_date) AS month,
				CASE WHEN(amount > 0) THEN 'inkomen' ELSE 'lasten' END AS stack,
				sum(amount) AS total_amount
			FROM entry
			GROUP BY 
				date_part('year', value_date),
				date_part('month', value_date),
				CASE WHEN(amount > 0) THEN 'inkomen' ELSE 'lasten' END
			ORDER BY
				date_part('year', value_date) ASC,
				date_part('month', value_date) ASC
		");

		return $records;
	}
}