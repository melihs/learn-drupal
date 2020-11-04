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
 *   category = @Translation("exchange custom block")
 * )
 */
class ExchangeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    return [
      '#markup' => $this->t($config['currency'])
    ];
  }

  /**
   * {@inheritDoc}
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['currency'] = [
      '#type'          => 'select',
      '#title'         => $this->t('para birimi'),
      '#options'       => [
        'USD' => 'DOLAR',
        'EUR' => 'EURO',
        'TRY' => 'TÜRK LİRASI'
      ],
      '#required'      => TRUE,
      'description'    => $this->t('para birimine göre güncel kurun karşılığı ekranda gösterilecek'),
      '#default_value' => isset($config['currency']) ? $config['currency'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $this->configuration['currency'] = $form_state->getValue('currency');

  }

}
