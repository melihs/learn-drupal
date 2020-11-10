<?php


namespace Drupal\exchange_service\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\exchange_service\Constants\ExchangeConstants;

class AddCurrency extends ConfigFormBase
{
  private $currencies;
  private $types;

  public function __construct()
  {
    $this->currencies = ExchangeConstants::CURRENCIES;
    $this->types = ExchangeConstants::TYPES;
  }

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
    return 'exchange_service.add';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $default_currency = array_keys($this->currencies);
    $i = 0;

    foreach ($this->types as $key => $value) {
      $form['group_'. $key] = [
          '#tree' => TRUE,
      ];

      $form['group_'. $key]['currency'] = [
          '#type' => 'select',
          '#title' => 'Currency',
          '#options' => $this->currencies,
          '#default_value' => $default_currency[$i++],
      ];

      $form['group_'. $key]['type'] = [
          '#type' => 'select',
          '#title' => 'Currency Code',
          '#options' => $this->types,
          '#default_value' => $key,
      ];

      $form['group_'. $key]['rate'] = [
          '#type' => 'number',
          '#min' => 0,
          '#step' => 0.1,
          '#required' => TRUE,
          '#title' => 'Rate',
          '#default_value' => 0,
      ];

      $form['group_'. $key]['created_at'] = [
          '#type' => 'date',
          '#title' => 'Exchange create date',
          '#date_format' =>'Y-m-d',
          '#default_value' =>date('Y-m-d'),
      ];
    }

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('save'),
        '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $types = array_keys($this->types);
    $this->config('exchange_service.add')
        ->set($types[0], $form_state->getValue('group_'.$types[0]))
        ->set($types[1], $form_state->getValue('group_'.$types[1]))
        ->set($types[2], $form_state->getValue('group_'.$types[2]))
        ->set($types[3], $form_state->getValue('group_'.$types[3]))
        ->set($types[4], $form_state->getValue('group_'.$types[4]))
        ->save();

    $form_state->setRedirect('exchange_service.list');
  }
}