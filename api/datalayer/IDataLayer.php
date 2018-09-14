<?php
namespace datalayer;

interface IDataLayer {
	
	function getMonths();

	function getCategories();

	function getCategoriesAndGroups();

	function getReportingData() : IReportingDataLayer;

	function getStatementsData() : IStatementsDataLayer;

	function getImportData() : \IImportDataLayer;

	function getTransactionsData() : ITransactionsDataLayer;


}