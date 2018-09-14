<?php
require '..\lib\importer\Importer.php';

$importDirectory = ".\import\\";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class ImportLogger implements IImportLogger {
	public function logMessage($message) {
		echo "- " . $message . "\n";
	}
}

$logger = new ImportLogger();

$logger->logMessage("Started ABN Financieel import tool");

$config = parse_ini_file(".\config.ini", true);

// connect to the postgresql database
$conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
    $config['db']['host'], 
    $config['db']['port'], 
    $config['db']['database'], 
    $config['db']['user'], 
    $config['db']['password']);

$logger->logMessage("Setting up database connection to '" . $config['db']['database'] . "'...");

$conn = new \PDO($conStr);

$logger->logMessage("Database connection established");

$logger->logMessage("Scanning import directory '" . $importDirectory . " for CSV files");
$files = scandir($importDirectory);

$files = array_filter($files, function ($file) { 
	if (substr($file, 0, 1) != '.') return $file;
});

$logger->logMessage("Found " . count($files) . " files in import directory");

$importer = new Importer($conn);
$importer->addLogger($logger);

$importStatementCount = 0;
$importEntryCount = 0;
$duplicateCount = 0;
foreach($files as $file) {	
	try {
		$importer->importFile($importDirectory . $file);
	} catch (DuplicateStatementException $e) {
		$logger->logMessage("Statement already present in database");
		$duplicateCount++;
	} catch (Exception $e) {
		$logger->logMessage("ERROR: " . $e->getMessage());
		die();
	}
}

$logger->logMessage("Summary:");

$logger->logMessage("Aantal statements geimporteerd: " . $importStatementCount);
$logger->logMessage("Aantal entries geimporteerd: " . $importEntryCount);
$logger->logMessage("Aantal oude statements gevonden: " . $duplicateCount);

$logger->logMessage("Klaar!");



