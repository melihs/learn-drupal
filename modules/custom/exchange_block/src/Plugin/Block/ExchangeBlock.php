<?php

namespace Drupal\exchange_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\exchange_block\Constants\ExchangeConstants;

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

    private $currency;
    private $uri;

    public function __construct(array $configuration, $plugin_id, $plugin_definition)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);

        $this->currency = ExchangeConstants::CURRENCY;

        $this->uri = ExchangeConstants::URI;
    }


    /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $theme = "exchange";

    $response = \Drupal::httpClient()->get($this->uri.$config['currency_to']);

    $data = json_decode((string) $response->getBody(), TRUE);

    foreach ($data['rates'] as $currency => $value) {
      if ($currency === $config['currency_from']) {
          $result = round($value, 3);

          $update_date = $data['date'];

          break;
      }
    }

    return [
      '#theme'  => $theme,
      '#cache' => [
        'max-age' => 0
      ],
      '#currency_from' => $config['currency_from'],
      '#currency_to' => $config['currency_to'],
      '#result' => isset($result) ? $result : 'sonuç bulunamadı',
      '#update_date' => isset($update_date) ? $update_date : '-',
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
      '#options' => $this->currency,
      '#required' => TRUE,
      '#description' => $this->t('seçili para birimi'),
      '#default_value' => isset($config['currency_from']) ? $config['currency_from'] : 'TRY',
    ];

    $form['currency_to'] = [
      '#type' => 'select',
      '#title' => $this->t('para birimi'),
      '#options' => $this->currency,
      '#required' => TRUE,
      '#description' => $this->t('seçili döviz kuruna karşılık gelecek para birimi'),
      '#default_value' => isset($config['currency_to']) ? $config['currency_to'] : 'USD',
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
