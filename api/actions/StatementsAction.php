<?php

class StatementsAction
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

		if (!in_array($sortBy, array('start_balance_date'))) $sortBy = 'start_balance_date';

        $records = $this->container->dataLayer->getStatementsData()->getStatements($year, $month, $sortBy, $sortOrder);

        $data = array();
		foreach($records as $record) {
			$data[] = $record;
		}

		return $response->withJson($data);
	}

	public function import ($request, $response, $args) {
		$files = $request->getUploadedFiles();

		if (empty($files)) return $response->withJson("Missing files")->withStatus(400);

		$importDataLayer = $this->container->dataLayer->getImportData();
		$importer = new Importer($importDataLayer);

		$result = array();
		foreach($files as $file) {
			$fileName = $file->getClientFilename();

			if ($file->getError() !== UPLOAD_ERR_OK) {
				$result[] = array('name' => $fileName, 'success' => false, 'error' => 'Upload not OK');
				continue;
			}

			$error = null;
			try {
				$importer->importFile($file->file);
			} catch (DuplicateStatementException $e) {
				$error = "Statement already present in database";
			} catch (Exception $e) {
				$error = $e->getMessage();
			}

			$result[] = array('name' => $fileName, 'success' => ($error == null), 'error' => $error);
		}

		return $response->withJson($result);
	}

	public function delete($request, $response, $args) {
		$params = $request->getParsedBody();

		if (empty($params['month'])) {
			return $response->withJson("Missing month parameter")->withStatus(400);
		}

		if (empty($params['year'])) {
			return $response->withJson("Missing year parameter")->withStatus(400);
		}

		if (!is_numeric($params['month'])) {
			return $response->withJson("Invalid month parameter; must be numeric")->withStatus(400);
		}

		if (!is_numeric($params['year'])) {
			return $response->withStatus(400)->withJson("Invalid year parameter; must be numeric");
		}

		$this->container->dataLayer->getStatementsData()->deleteTransactions($params['month'], $params['year']);
		$this->container->dataLayer->getStatementsData()->deleteStatements($params['month'], $params['year']);

		return $response->withJson(true);
	}
}