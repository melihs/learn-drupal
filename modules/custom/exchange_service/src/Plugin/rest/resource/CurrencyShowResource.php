<?php


namespace Drupal\exchange_service\Plugin\rest\resource;

use Drupal\exchange_service\Dao\ExchangeServiceDao;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Show Currency for get method
 *
 * @RestResource(
 *   id = "currency_show_resource",
 *   label = @Translation("Exchange Show Currency Endpoint"),
 *   uri_paths = {
 *     "canonical" = "/api/currency/{currency}"
 *   }
 * )
 */
class CurrencyShowResource extends ResourceBase
{
  public function get($currency)
  {
    $response = ExchangeServiceDao::show($currency);

    return new ResourceResponse($response);
  }
}