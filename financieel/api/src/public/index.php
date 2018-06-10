<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require '../config.php';

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];

	$connStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
    $db['host'], 
    $db['port'], 
    $db['database'], 
    $db['user'], 
    $db['password']);

    $pdo = new PDO($connStr);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/transacties', TransactiesAction::class);
$app->get('/dates/months', DatesAction::class  . ':months');


$app->run();
