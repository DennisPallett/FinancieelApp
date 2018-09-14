<?php
require_once (dirname(__FILE__) . '/../../lib/importer/Classifier.php');

class TransactiesAction
{
	protected $container;

	public function __construct(Slim\Container $container) {
		$this->container = $container;
	}

    public function __invoke($request, $response, $args) {
		$params = $request->getQueryParams();

		$year = null;
		$month = null;

		if (!empty($params['month']) && is_numeric($params['month'])) 
			$month = $params['month'];

		if (!empty($params['year']) && is_numeric($params['year'])) 
			$year = $params['year'];

		$sortBy = "";
		$sortOrder = "";

		if (!empty($params['sortby'])) $sortBy = $params['sortby'];
		if (!empty($params['sortorder'])) $sortOrder = $params['sortorder'];

		$sortOrder = strtoupper($sortOrder);
		if ($sortOrder != 'ASC' && $sortOrder != 'DESC') $sortOrder = 'ASC';

		if (!in_array($sortBy, array('amount', 'value_date'))) $sortBy = 'amount';

        $records = $this->container->dataLayer->getTransactionsData()->getTransactions($year, $month, $sortBy, $sortOrder);

        $data = array();
		foreach($records as $record) {
			$record['is_card_payment'] = ($record['is_card_payment'] == '1');
			$record['is_cash_withdrawal'] = ($record['is_cash_withdrawal'] == '1');
			$record['is_shop_sale'] = ($record['is_shop_sale'] == '1');

			$record['shop_card_payment'] = $this->parseShopCardPayment($record);
			$data[] = $record;
		}

		return $response->withJson($data);
	}

	public function classify ($request, $response, $args) {
		$importDataLayer = $this->container->dataLayer->getImportData();
		$classifier = new Classifier($importDataLayer);
		$classified = $classifier->classify();

		$data = array(
			'classifiedCount' => $classifier->classifiedCount,
			'unclassifiedCount' => $classifier->unclassifiedCount,
			'classified' => $classified
		);

		return $response->withJson($data);
	}

	public function updateCategory($request, $response, $args) {
		$params = $request->getParsedBody();

		if (empty($params['id']) || !is_numeric($params['id'])) {
			return $response->withStatus(400);
		}

		if (!isset($params['category'])) {
			return $response->withStatus(400);
		}

		$this->container->dataLayer->getTransactionsData()->updateCategory($params['id'], $params['category']);
		return $response->withJson(true);
	}

	private function parseShopCardPayment ($record) {
		if	($record['is_card_payment'] == false || $record['is_shop_sale'] == false)
			return null;

		$regex = '/';
		$regex .= 'BEA(.*):';
		
		// payment NR
		$regex .= '(?P<nr>.*)';
		$regex .= ' ';
		
		// date
		$regex .= '(?P<day>[0-9]{2})\.(?P<month>[0-9]{2})\.(?P<year>[0-9]{2,4})';
		$regex .= '\/';

		// time
		$regex .= '(?P<hour>[0-9]{2})\.(?P<minutes>[0-9]{2})';
		
		// description
		$regex .= '(?P<description>.*),PAS';
		
		$regex .= '/';

		preg_match($regex, $record['description'], $matches);

		if (count($matches) == 0)
			return null;

		if (strlen($matches['year']) < 4) $matches['year'] = '20' . $matches['year'];

		$data = array(
			'nr' => $matches['nr'],
			'description' => $matches['description'],
			'date' => $matches['year'] . '-' . $matches['month'] . '-' . $matches['day'],
			'time' => $matches['hour'] . ':' . $matches['minutes'] . ':00',
			'timestamp' => $matches['year'] . '-' . $matches['month'] . '-' . $matches['day'] . ' ' . $matches['hour'] . ':' . $matches['minutes'] . ':00'
		);

		return $data;
	}

	private function loadData ($request) {
		$params = $request->getQueryParams();

		$bindParams = array();

		$sql = "SELECT 
		entry.*, COALESCE(category.name, entry.category) AS category_name 
		FROM entry LEFT JOIN category ON entry.category = category.key WHERE 1=1";

		if (!empty($params['month']) && is_numeric($params['month'])) {
			$sql .= " AND date_part('month', value_date) = :month";
			$bindParams[':month'] = $params['month'];
		}

		if (!empty($params['year']) && is_numeric($params['year'])) {
			$sql .= " AND date_part('year', value_date) = :year";
			$bindParams[':year'] = $params['year'];
		}

		$sql .= " ORDER BY " . $sortBy . ' ' . $sortOrder;

		$statement = $this->container->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$statement->execute($bindParams);

		return $statement->fetchAll();
	}
}