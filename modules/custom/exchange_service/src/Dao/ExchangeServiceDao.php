<?php

namespace Drupal\exchange_service\Dao;

use Drupal\exchange_service\Constants\ExchangeConstants;

class ExchangeServiceDao
{

//  /**
//   * @param $params
//   */
//  public static function add($params)
//  {
//    try {
//      $connection = Database::getConnection();
//
//      $connection->insert('add_currency')
//          ->fields($params)
//          ->execute();
//
//    }catch (\Exception $e) {
//      \Drupal::logger('exchange_service')->error('database insert error: '.$e->getMessage());
//    }
//  }

  /**
   * @return array
   */
  public static function all() : array
  {
    $config = \Drupal::config('exchange_service.add');

    $types = array_keys(ExchangeConstants::TYPES);

     return [
        $config->get($types[0]),
        $config->get($types[1]),
        $config->get($types[2]),
        $config->get($types[3]),
        $config->get($types[4]),
    ];
  }

  /**
   * @param $id
   * @return array|mixed|null
   */
  public static  function  show($id)
  {
    $config = \Drupal::config('exchange_service.add');

    $types = array_keys(ExchangeConstants::TYPES);
    switch ($id) {
      case $types[0];
        return $config->get($types[0]);
      case $types[1]:
        return $config->get($types[1]);
      case $types[2]:
        return $config->get($types[2]);
      case $types[3]:
        return $config->get($types[3]);
      case $types[4]:
        return $config->get($types[4]);
    }
  }
}