<?php


namespace Drupal\exchange_service\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\exchange_service\Constants\ExchangeConstants;

class EditCurrency extends ConfigFormBase
{
  private $currencies;
  private $types;
  private $id;

  public function __construct()
  {
    $this->currencies = ExchangeConstants::CURRENCIES;

    $this->types = ExchangeConstants::TYPES;
  }

  /**
   * @return array|string[]
   */
  public function getEditableConfigNames()
  {
    return ['exchange_service.add'];
  }

  /**
   * {@inheritDoc}
   * @return string|void
   */
  public function getFormId()
  {
    return 'exchange_service.edit';
  }

  /**
   * @param  array  $form
   * @param  FormStateInterface  $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $this->id = \Drupal::request()->get('id');

    $base_url = "http://$_SERVER[HTTP_HOST]";

    $request = \Drupal::httpClient()->get($base_url.'/api/currency/'. $this->id);

    $response = json_decode((string) $request->getBody(), TRUE);

    $form['group_'. $this->id] = [
        '#tree' => TRUE,
    ];

    $form['group_'. $this->id]['currency'] = [
        '#type' => 'select',
        '#title' => 'Para birimi',
        '#options' => $this->currencies,
        '#default_value' => isset($response['currency']) ? $response['currency'] : "",
    ];

    $form['group_'. $this->id]['type'] = [
        '#type' => 'select',
        '#title' => 'Para birimi Kodu',
        '#options' => $this->types,
        '#default_value' => isset($response['type']) ? $response['type'] : "",
    ];

    $form['group_'. $this->id]['rate'] = [
        '#type' => 'number',
        '#min' => 0,
        '#step' => 0.01,
        '#required' => TRUE,
        '#title' => 'Oranı',
        '#default_value' => isset($response['rate'] ) ? $response['rate']  : '',
    ];

    $form['group_'. $this->id]['created_at'] = [
        '#type' => 'date',
        '#title' => 'kur tarihi',
        '#required' => TRUE,
        '#default_value' => isset($response['created_at'] ) ? $response['created_at']  : date('d-m-Y'),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('update'),
        '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * @param  array  $form
   * @param  FormStateInterface  $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $this->config('exchange_service.add')
        ->set($this->id, $form_state->getValue('group_'.$this->id))
        ->save();

    $form_state->setRedirect('exchange_service.list');
  }
}