<?php

// locate file that contains config path
$configPathFile = dirname(__FILE__) . '/config.path';
if (!file_exists($configPathFile))
{
	die("ERROR: unable to find config path file: " . $configPathFile);
}

$configFile = file_get_contents($configPathFile);

if (!file_exists($configFile)) {
	die("ERROR: unable to read config file: " . $configFile);
}

// load config file
require $configFile;

// run app
$configPath = dirname($configFile);
require $configPath . '/run.php';