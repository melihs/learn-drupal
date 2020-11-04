<?php

namespace Drupal\exchange_block\Plugin\Block;


use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'Exchange Block' Block.
 *
 * @Block(
 *   id = "exchange_block",
 *   admin_label = @Translation("Döviz kuru"),
 *   category = @Translation("exchange custom block")
 * )
 */
class ExchangeBlock extends BlockBase {

  const CURRENCY = [
      'USD' => 'DOLAR',
      'EUR' => 'EURO',
      'TRY' => 'TÜRK LİRASI'
  ];

  const URI = 'https://api.exchangeratesapi.io/latest?base=';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $theme = "exchange";

    if (empty($config)) {
      $currency_from = $this->t('para birimi seçilmedi!');
      $currency_to = $this->t('para birimi seçilmedi!');
    }else {
      $currency_from = $config['currency_from'];
      $currency_to = $config['currency_to'];
    }

    $response = \Drupal::httpClient()->get(self::URI.$currency_from);

    $rates = json_decode((string) $response->getBody(), TRUE)['rates'];
//    $rates = $data['rates'];

//    $result = 0;
    foreach ($rates as $currency => $value) {
      if ($currency === $currency_to) {
        $result = round($value, 2);
      }
    }

    return [
      '#theme'  => $theme,
      '#cache' => [
        'max-age' => 0
      ],
      '#currency_from' => $currency_from,
      '#currency_to' => $currency_to,
      '#result' => $result,
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


    $form['currency_from'] = [
      '#type' => 'select',
      '#title' => $this->t('para birimi'),
      '#options' => self::CURRENCY,
      '#required' => TRUE,
      '#description' => $this->t('seçili para birimi'),
      '#default_value' => isset($config['currency_from']) ? $config['currency_from'] : '',
    ];
    $form['currency_to'] = [
      '#type' => 'select',
      '#title' => $this->t('para birimi'),
      '#options' => self::CURRENCY,
      '#required' => TRUE,
      '#description' => $this->t('seçili döviz kuruna karşılık gelecek para birimi'),
      '#default_value' => isset($config['currency_to']) ? $config['currency_to'] : '',
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

    $this->configuration['currency_from'] = $form_state->getValue('currency_from');
    $this->configuration['currency_to'] = $form_state->getValue('currency_to');

  }

}
