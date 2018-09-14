<?php

class CategoriesAction
{
   protected $container;

   public function __construct(Slim\Container $container) {
       $this->container = $container;
   }

   public function groups ($request, $response, $args) {
		$records = $this->container->dataLayer->getCategoriesAndGroups();

		$data = array();
		foreach($records as $record) {
			$groupKey = $record['categorygroup_key'];

			if (!isset($data[$groupKey])) {
				$data[$groupKey] = array('key' => $groupKey, 'name' => $record['categorygroup_name'], 'categories' => array());
			}

			$data[$groupKey]['categories'][] = array('key' => $record['category_key'], 'name' => $record['category_name']);
		}

		$data = array_values($data);

		return $response->withJson($data);
   }

   public function __invoke($request, $response, $args) {
		$categories = $this->container->dataLayer->getCategories();

		$data = array();
		foreach($categories as $category) {
			$record = $category;

			$data[] = $record;
		}

		return $response->withJson($data);
   }
}