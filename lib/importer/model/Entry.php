<?php
require 'Party.php';

class Entry {
	public $id;

	public $bookingDate;

	public $valueDate;

	public $amount;

	public $description;

	public $otherParty;

	public $remittanceInfo;

	public $isCardPayment = false;

	public $isCashWithdrawal = false;

	public $isShopSale = false;

	public $startBalanceAmount;

	public $endBalanceAmount;

	public function  __construct($entryXml)
	{
		$this->id = (string) $entryXml->AcctSvcrRef;
		$this->bookingDate = (string) $entryXml->BookgDt->Dt;
		$this->valueDate = (string) $entryXml->ValDt->Dt;
		$this->amount = (double) $entryXml->Amt;

		if ($entryXml->CdtDbtInd == 'DBIT')
		{
			$this->amount = $this->amount * -1;
		}

		$this->description = (string) $entryXml->AddtlNtryInf;

		$this->processPaymentType($entryXml->BkTxCd->Domn->Fmly);

		$this->processDetails($entryXml->NtryDtls->TxDtls);
	}

	private function processPaymentType($typeXml) {
		$code = (string) $typeXml->Cd;
		$subCode = (string) $typeXml->SubFmlyCd;

		// is Customer Card Transaction?
		$this->isCardPayment = ($code == 'CCRD');
		$this->isCashWithdrawal = ($subCode == 'CWDL');
		$this->isShopSale = ($subCode == 'POSD');
	}

	private function processDetails($detailsXml)
	{
		if (!empty($detailsXml->RltdPties))
			$this->processRelatedParties($detailsXml->RltdPties);

		if (!empty($detailsXml->RmtInf) && !empty($detailsXml->RmtInf->Ustrd))
			$this->remittanceInfo = (string) $detailsXml->RmtInf->Ustrd;
	}

	private function processRelatedParties($partiesXml)
	{
		if (!empty($partiesXml->Cdtr)) {
			$this->otherParty = new Party();
			$this->otherParty->processPartyXml($partiesXml->Cdtr);

			if (!empty($partiesXml->CdtrAcct)) {
				$this->otherParty->processAccountXml($partiesXml->CdtrAcct);
			}

		} else if (!empty($partiesXml->Dbtr)) {
			$this->otherParty = new Party();
			$this->otherParty->processPartyXml($partiesXml->Dbtr);

			if (!empty($partiesXml->DbtrAcct)) {
				$this->otherParty->processAccountXml($partiesXml->DbtrAcct);
			}
		}
	}
}