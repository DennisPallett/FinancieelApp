<?php
namespace datalayer\database;

require (dirname(__FILE__) . '/../../../lib/importer/Importer.php');

abstract class ImportDataLayer implements \IImportDataLayer {
	private $db;

	private $insertStatementQuery;

	private $insertEntryQuery;

	private $updateEntryCategoryQuery;

	public function __construct($db) {
		$this->db = $db;

		$this->insertStatementQuery = $db->prepare("
			INSERT INTO statement (id, creation_datetime, start_balance_date, start_balance_amount, end_balance_date, end_balance_amount) 
			VALUES (?, ?, ?, ?, ?, ?)
		");

		$this->insertEntryQuery = $db->prepare("
			INSERT INTO entry (statement_id, reference, booking_date, value_date, amount, description, 
			other_party_name, other_party_address, other_party_account, remittance_info,
			is_card_payment, is_cash_withdrawal, is_shop_sale, start_balance_amount, end_balance_amount) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		");

		$this->updateEntryCategoryQuery = $db->prepare("UPDATE entry SET category = ? WHERE id = ?");
	}

	function beginTransaction () {
		$this->db->beginTransaction();
	}

	function saveStatement (\BankStatement $statement) {
		$this->insertStatementQuery->bindValue(1, $statement->id);
		$this->insertStatementQuery->bindValue(2, $statement->creationDateTime);
		$this->insertStatementQuery->bindValue(3, $statement->startBalance->date);
		$this->insertStatementQuery->bindValue(4, $statement->startBalance->amount);
		$this->insertStatementQuery->bindValue(5, $statement->endBalance->date);
		$this->insertStatementQuery->bindValue(6, $statement->endBalance->amount);

		$ret = false;
		try {
			$ret = $this->insertStatementQuery->execute();
		} catch (Exception $e) {
		}

		if (!$ret) {
			$errorInfo = $this->insertStatementQuery->errorInfo();
			$errorCode = $errorInfo['0'];
			$errorMessage = $errorInfo['2'];

			if ($errorCode == '23505') {
				throw new DuplicateStatementException();
			} else {
				throw new Exception($errorMessage . ' (' . $errorCode . ')');
			}
		}
	}

	function saveEntry (\BankStatement $statement, \Entry $entry) {
		$this->insertEntryQuery->bindValue(1, $statement->id);
		$this->insertEntryQuery->bindValue(2, $entry->id);
		$this->insertEntryQuery->bindValue(3, $entry->bookingDate);
		$this->insertEntryQuery->bindValue(4, $entry->valueDate);
		$this->insertEntryQuery->bindValue(5, $entry->amount);
		$this->insertEntryQuery->bindValue(6, $entry->description);

		if ($entry->otherParty != null) {
			$this->insertEntryQuery->bindValue(7, $entry->otherParty->name);
			$this->insertEntryQuery->bindValue(8, $entry->otherParty->address);
			$this->insertEntryQuery->bindValue(9, $entry->otherParty->account);
		} else {
			$this->insertEntryQuery->bindValue(7, null);
			$this->insertEntryQuery->bindValue(8, null);
			$this->insertEntryQuery->bindValue(9, null);
		}

		$this->insertEntryQuery->bindValue(10, $entry->remittanceInfo);

		$this->insertEntryQuery->bindValue(11, (int) $entry->isCardPayment);
		$this->insertEntryQuery->bindValue(12, (int) $entry->isCashWithdrawal);
		$this->insertEntryQuery->bindValue(13, (int) $entry->isShopSale);
		$this->insertEntryQuery->bindValue(14, $entry->startBalanceAmount);
		$this->insertEntryQuery->bindValue(15, $entry->endBalanceAmount);

		$ret = false;
		try {
			$ret = $this->insertEntryQuery->execute();
		} catch (Exception $e) {
		}

		if (!$ret) {
			$errorInfo = $this->_insertEntryQuery->errorInfo();
			$errorCode = $errorInfo['0'];
			$errorMessage = $errorInfo['2'];

			throw new Exception($errorMessage . ' (' . $errorCode . ')');
		}
	}

	function rollback() {
		$this->db->rollback();
	}

	function commit() {
		$this->db->commit();
	}

	function loadUnclassifiedEntries () {
		return $this->db->query("SELECT * FROM entry WHERE category IS NULL");
	}

	function updateEntryCategory ($entryId, $category) {
		// update in database
		$this->updateEntryCategoryQuery->bindValue(1, $category);
		$this->updateEntryCategoryQuery->bindValue(2, $entryId);

		$ret = false;
		try {
			$ret = $this->updateEntryCategoryQuery->execute();
		} catch (Exception $e) {
		}

		if (!$ret) {
			$errorInfo = $this->updateEntryCategoryQuery->errorInfo();
			$errorCode = $errorInfo['0'];
			$errorMessage = $errorInfo['2'];

			throw new Exception($errorMessage . ' (' . $errorCode . ')');
		}
	}
}