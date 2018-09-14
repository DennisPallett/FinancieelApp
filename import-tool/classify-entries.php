<?php
require '..\lib\importer\Classifier.php';

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class ImportLogger implements IImportLogger {
	public function logMessage($message) {
		echo "- " . $message . "\n";
	}
}

$logger = new ImportLogger();

$logger->logMessage("Started Entry Classifier tool");

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

$classifier = new Classifier($conn);
$classifier->addLogger($logger);
$classifier->classify();

$logger->logMessage("Summary:");

$logger->logMessage("Aantal entries classified: " . $classifier->classifiedCount);
$logger->logMessage("Aantal entries unclassified: " . $classifier->unclassifiedCount);

$logger->logMessage("Klaar!");