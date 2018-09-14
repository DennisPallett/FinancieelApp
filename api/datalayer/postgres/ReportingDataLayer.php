<?php
namespace datalayer\postgres;

class ReportingDataLayer extends \datalayer\database\ReportingDataLayer {
	
	private $db;

	public function __construct($db) {
		parent::__construct($db);
		$this->db = $db;
	}

}