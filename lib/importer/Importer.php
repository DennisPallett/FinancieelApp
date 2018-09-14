<?php
require_once 'IImportLogger.php';
require_once 'IImportDataLayer.php';
require_once 'model/BankStatement.php';

class Importer {
	public $importEntryCount = 0;

	private $_loggers = array();

	private $_dataLayer;

	public function __construct(IImportDataLayer $dataLayer) {
		$this->_dataLayer = $dataLayer;
	}

	public function addLogger(IImportLogger $logger) {
		$this->_loggers[] = $logger;
	}

	public function importFile($file) {
		// convert XML file into object
		$this->logMessage("Processing file " . $file . " ...");

		$statement = new BankStatement();
		$statement->processFile($file);

		$this->logMessage("File processed");

		$this->logMessage("Inserting statement into database...");

		$this->_dataLayer->beginTransaction();

		try {
			$this->insertStatement($statement);
			$this->insertEntries($statement);
		} catch (Exception $e) {
			$this->_dataLayer->rollback();
			throw $e;
		}
		
		$this->_dataLayer->commit();
	}

	private function insertStatement ($statement) {
		$this->_dataLayer->saveStatement($statement);
		$this->logMessage("Statement inserted");
	}

	private function insertEntries ($statement) {
		$this->logMessage("Inserting entries...");

		$startBalanceAmount = $statement->startBalance->amount;
		foreach($statement->entries as $entry) {
			$endBalanceAmount = $startBalanceAmount + $entry->amount;

			$entry->startBalanceAmount = $startBalanceAmount;
			$entry->endBalanceAmount = $endBalanceAmount;

			$this->_dataLayer->saveEntry($statement, $entry);

			$startBalanceAmount = $endBalanceAmount;

			$this->importEntryCount++;
		}

		$this->logMessage("Entries inserted");
	}

	private function logMessage($message) {
		foreach($this->_loggers as $logger) {
			$logger->logMessage($message);
		}
	}
}

class DuplicateStatementException extends Exception {
}