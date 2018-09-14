<?php
require 'Balance.php';
require 'Entry.php';

class BankStatement {
	public $id;

	public $creationDateTime;

	public $startBalance;

	public $endBalance;

	public $entries = array();

	private $currentBalanceAmount;

	private $statement;

	public function processFile(string $file) {
		// TODO: validate against XSD

		$str = file_get_contents($file);
		$temp = mb_convert_encoding( $str, "UTF-8" );
		$xml = simplexml_load_string($temp);

		$this->statement = $xml->BkToCstmrStmt->Stmt;
		if ($this->statement == null)
		{
			throw new Exception("Unable to find statement element!");
		}

		$this->processStatement();
		$this->processBalances();
		$this->processEntries();
	}

	private function processStatement ()
	{
		$this->id = (string) $this->statement->Id;
		$this->creationDateTime = (string) $this->statement->CreDtTm;
	}

	private function processBalances () 
	{
		foreach($this->statement->Bal as $balance) {
			$date = (string) $balance->Dt->Dt;
			$amount = (double) $balance->Amt;

			// indien het een debit balans is dan moet het een negatief getal zijn
			if ($balance->CdtDbtInd == 'DBIT') {
				$amount = $amount * -1;
			}

			$obj = new Balance();
			$obj->amount = $amount;
			$obj->date = $date;

			if ($balance->Tp->CdOrPrtry->Cd == 'PRCD') {
				$this->startBalance = $obj;
				$this->currentBalanceAmount = $amount;
			} else {
				$this->endBalance = $obj;
			}
		}
	}

	private function processEntries ()
	{
		foreach($this->statement->Ntry as $entryXml) {
			$entry = new Entry($entryXml);
			$this->entries[] = $entry;
		}
	}
}