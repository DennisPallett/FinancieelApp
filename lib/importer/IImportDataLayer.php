<?php

interface IImportDataLayer {

	function beginTransaction ();

	function saveStatement (BankStatement $statement);

	function saveEntry (BankStatement $statement, Entry $entry);

	function rollback();

	function commit();

	function loadUnclassifiedEntries ();

	function updateEntryCategory ($entryId, $category);

}