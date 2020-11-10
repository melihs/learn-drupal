<?php

namespace Drupal\exchange_service\Plugin\rest\resource;

use Drupal\exchange_service\Dao\ExchangeServiceDao;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "exchange_resource",
 *   label = @Translation("Exchange List Currency Endpoint"),
 *   uri_paths = {
 *     "canonical" = "/api/currency"
 *   }
 * )
 */
class ExchangeResource extends ResourceBase
{
  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get()
  {
    $results = ExchangeServiceDao::all();

    $response = [
        'rates' => $results,
        'base' => 'TRY',
    ];

    return new ResourceResponse($response);
  }
}