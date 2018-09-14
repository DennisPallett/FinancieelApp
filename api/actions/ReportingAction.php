<?php

class ReportingAction
{
   protected $container;

   public function __construct(Slim\Container $container) {
       $this->container = $container;
   }

   public function balance ($request, $response, $args) {
		$records = $this->container->dataLayer->getReportingData()->getBalances();

		$data = array();
		foreach($records as $record) {
			$data[] = array(
				'date' => $record['start_balance_date'], 
				'balance' => $record['start_balance_amount'],
				'timestamp' => strtotime($record['start_balance_date'])
			);

			$data[] = array(
				'date' => $record['end_balance_date'], 
				'balance' => $record['end_balance_amount'],
				'timestamp' => strtotime($record['end_balance_date'])
			);
		}

		usort($data, function ($a, $b) {
			if ($a['timestamp'] == $b['timestamp']) {
				return 0;
			}
			return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
		});

		return $response->withJson($data);	
   }

   public function categoryByMonth($request, $response, $args) {
		$records = $this->container->dataLayer->getReportingData()->getAmountsByCategory();

		$series = array();
		$categories = array();
		foreach($records as $record) {
			$yearMonth = $record['year'] .'-' . $record['month'];

			$categories[] = $yearMonth;

			$category = $record['stack'];

			if (!isset($series[$category])) $series[$category] = array();

			$series[$category][$yearMonth] = $record['total_amount'];
		}

		$categories = array_unique($categories);

		$data['series'] = array();
		foreach($series as $serieName => $values) {
			$serie = array('name' => $serieName, 'data' => array());

			foreach($categories as $category) {
				$value = (double)((isset($values[$category])) ? $values[$category] : 0);
				$value = abs($value);
				$serie['data'][] = $value;
			}

			$data['series'][] = $serie;
		}

		$data['categories'] = array();
		foreach($categories as $category) {
			$data['categories'][] = strftime('%b %Y', strtotime($category . '-01'));
		}

		return $response->withJson($data);
   }
}