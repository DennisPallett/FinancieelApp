<?php

class DatesAction
{
   protected $container;

   public function __construct(Slim\Container $container) {
       $this->container = $container;
   }

   public function months($request, $response, $args) {
		$records = $this->container->dataLayer->getMonths();

		$data = array();
		foreach($records as $month) {
			$record = array();
			$record['month'] = $month['month'];
			$record['year'] = $month['year'];

			$data[] = $record;
		}

		return $response->withJson($data);
   }
}