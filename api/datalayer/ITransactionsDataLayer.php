<?php
namespace datalayer;

interface ITransactionsDataLayer {
	function updateCategory($transactionId, $category);

	function getTransactions($year, $month, $sortBy, $sortOrder);
}