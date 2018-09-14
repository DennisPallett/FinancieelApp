<?php
require_once 'IImportLogger.php';
require_once 'IImportDataLayer.php';
require_once 'IRule.php';
require_once 'model/BankStatement.php';

class Classifier {
	public $classifiedCount;

	public $unclassifiedCount;

	private $_loggers = array();

	private $_rules = array();

	private $_ruleObjects = array();

	private $_entries = array();

	private $_updateQuery;

	private $_dataLayer;

	public function __construct(IImportDataLayer $dataLayer) {
		$this->_dataLayer = $dataLayer;
	}

	public function addLogger(IImportLogger $logger) {
		$this->_loggers[] = $logger;
	}

	public function classify() {
		$this->loadRules();

		$this->loadEntries();

		return $this->classifyEntries();
	}

	private function classifyEntries () {
		$this->logMessage("Classifying entries...");
		$this->classifiedCount = 0;
		$this->unclassifiedCount = 0;

		$results = array();
		foreach($this->_entries as $entry) {
			$category = $this->classifyEntry($entry);

			if ($category != null) {
				$results[] = array('id' => $entry['id'], 'category' => $category);
			}
		}

		$this->logMessage("Finished classification");
		return $results;
	}

	private function classifyEntry ($entry) {
		$category = null;

		foreach($this->_ruleObjects as $ruleObj) {
			$category = $ruleObj->classifyEntry($entry);

			// found a category -> stop classifying
			if ($category != null)
				break;
		}

		// no category found -> exit function
		if ($category == null)
		{
			$this->unclassifiedCount++;
			return;
		}

		// update entry with category
		$this->logMessage("Entry #" . $entry['id'] . " classified as: " . $category);

		// update in datalayer
		$this->_dataLayer->updateEntryCategory($entry['id'], $category);

		$this->classifiedCount++;

		return $category;
	}

	private function loadEntries () {
		$this->logMessage("Loading unclassified entries...");

		$this->_entries = $this->_dataLayer->loadUnclassifiedEntries();
		
		$this->logMessage("Loaded entries");
	}

	private function loadRules () {
		$this->logMessage("Loading classification rules...");

		foreach (glob(dirname(__FILE__) . "/rules/*.php") as $filename) {
			include $filename;
		}

		$classes = get_declared_classes();
		foreach($classes as $klass) {
			$reflect = new ReflectionClass($klass);
			if($reflect->implementsInterface('IRule')) 
			$this->_rules[] = $klass;
		}

		$this->logMessage("Found " . count($this->_rules) . " rules");

		if (count($this->_rules) == 0)
			throw new Exception("No classification rules available");

		foreach($this->_rules as $rule) {
			$this->_ruleObjects[] = new $rule();
		}
	}

	private function logMessage($message) {
		foreach($this->_loggers as $logger) {
			$logger->logMessage($message);
		}
	}
}