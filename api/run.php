<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$basePath = dirname(__FILE__);

require $basePath . '/vendor/autoload.php';

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['dataLayer'] = function ($c) {
	$db = $c['settings']['db'];

	$dbType = substr($db['connectionString'], 0, stripos($db['connectionString'], ':'));

	switch($dbType) {
		case 'pgsql':
			return new datalayer\postgres\DataLayer($db['connectionString'], $db['user'], $db['password']);
		case 'mysql':
			return new datalayer\mysql\DataLayer($db['connectionString'], $db['user'], $db['password']);
		default:
			throw new Exception('Unsupported database type: ' . $dbType);
	}
};

$app->get('/transacties', TransactiesAction::class);

$app->get('/statements', StatementsAction::class);
$app->post('/statements/delete', StatementsAction::class . ':delete');
$app->post('/statements/import', StatementsAction::class . ':import');

$app->post('/transacties/classify', TransactiesAction::class . ':classify');
$app->post('/transacties/update-category', TransactiesAction::class . ':updateCategory');

$app->get('/categories', CategoriesAction::class);
$app->get('/categories/groups', CategoriesAction::class . ':groups');

$app->get('/dates/months', DatesAction::class  . ':months');

$app->get('/reporting/category-by-month', ReportingAction::class  . ':categoryByMonth');
$app->get('/reporting/balance', ReportingAction::class  . ':balance');

$app->run();
