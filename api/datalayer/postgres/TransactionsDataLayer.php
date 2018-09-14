<?php
namespace datalayer\postgres;

class TransactionsDataLayer  extends \datalayer\database\TransactionsDataLayer {
	private $db;

	public function __construct($db) {
		parent::__construct($db);
		$this->db = $db;
	}

}
