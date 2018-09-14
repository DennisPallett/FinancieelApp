<?php
namespace datalayer\mysql;

class ImportDataLayer extends \datalayer\database\ImportDataLayer {
	private $db;

	public function __construct($db) {
		parent::__construct($db);
		$this->db = $db;
	}

}