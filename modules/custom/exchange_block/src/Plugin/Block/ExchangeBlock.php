<?php

namespace Drupal\exchange_block\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'Exchange Block' Block.
 *
 * @Block(
 *   id = "exchange_block",
 *   admin_label = @Translation("Döviz kuru bloku"),
 *   category = @Translation("example exhane block")
 * )
 */
class ExchangeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->getRandomContent(),
      '#cache' => [
        'max-age' => 0
      ]
    ];
  }

//  public function blockForm($form, FormStateInterface $form_state) {
//    $form = parent::blockForm($form, $form_state);
//
//    $config = $this->getConfiguration();
//
//    $form['exchange_block'] = [
//      '#type' => 'textfield'
//    ];
//
//  }

}
