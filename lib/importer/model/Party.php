<?php

class Party {
	public $name;

	public $address;

	public $account;

	public function processPartyXml ($partyXml) {
		if (!empty($partyXml->Nm))
			$this->name = (string) $partyXml->Nm;

		if (!empty($partyXml->PstlAdr)) {
			$addressList = array();
			foreach($partyXml->PstlAdr->AdrLine as $line) {
				$addressList[] = $line;
			}

			$this->address = implode($addressList, "\n");
		}
	}

	public function processAccountXml ($accountXml) {
		$this->account = (string) $accountXml->Id->IBAN;
	}
}