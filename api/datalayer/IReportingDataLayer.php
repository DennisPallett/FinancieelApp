<?php
namespace datalayer;

interface IReportingDataLayer {
	function getBalances ();

	function getAmountsByCategory ();
}