<?php
namespace datalayer\mysql;

class DataLayer extends \datalayer\database\DataLayer {
	private $db;

	private $reportingDataLayer;

	private $statementsDataLayer;

	private $importDataLayer;

	private $transactionsDataLayer;

	public function __construct($connectionString, $user, $password) {
		$this->db = new \PDO($connectionString, $user, $password);
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

		parent::__construct($this->db);

		$this->reportingDataLayer = new ReportingDataLayer($this->db);
		$this->statementsDataLayer = new StatementsDataLayer($this->db);
		$this->importDataLayer = new ImportDataLayer($this->db);
		$this->transactionsDataLayer = new TransactionsDataLayer($this->db);
	}

	public function quoteIdentifier ($identifier) {
		$split = explode('.', $identifier);
		$newIdentifier = array();
		foreach($split as $str) {
			$newIdentifier[] = '`' . $str . '`';
		}

		return implode('.', $newIdentifier);
	}

	function getReportingData () : \datalayer\IReportingDataLayer {
		return $this->reportingDataLayer;
	}

	function getStatementsData() : \datalayer\IStatementsDataLayer {
		return $this->statementsDataLayer;
	}

	function getImportData() : \IImportDataLayer {
		return $this->importDataLayer;
	}

	function getTransactionsData() : \datalayer\ITransactionsDataLayer {
		return $this->transactionsDataLayer;
	}

	function getMonths () {
		$records = $this->db->query("
			SELECT 
				EXTRACT(YEAR FROM value_date) as year,
				EXTRACT(MONTH FROM value_date) AS month
			FROM entry
			GROUP BY 
				EXTRACT(YEAR FROM value_date),
				EXTRACT(MONTH FROM value_date)
			ORDER BY
				EXTRACT(YEAR FROM value_date) DESC,
				EXTRACT(MONTH FROM value_date) DESC
		");

		return $records;
	}
}