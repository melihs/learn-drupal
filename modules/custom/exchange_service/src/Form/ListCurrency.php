<?php


namespace Drupal\exchange_service\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ListCurrency extends ConfigFormBase
{
    public function getEditableConfigNames()
    {
        return ['exchange_service.list'];
    }

    /**
     * {@inheritDoc}
     * @return string|void
     */
    public function getFormId()
    {
        return 'exchange_service_list_currency_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
      $base_url = "http://$_SERVER[HTTP_HOST]";

      $request = \Drupal::httpClient()->get($base_url.'/api/currency');

      $response = $this->filterResponse(json_decode((string) $request->getBody(), TRUE));

      $add_url = Url::fromRoute('exchange_service.add');

      $header = [
          'currency' => t('CURRENCY'),
          'type' => t('CURRENCY CODE'),
          'rate' => t('RATE'),
          'created_at' => t('DATE'),
          'opt' => t('UPDATE')
      ];


      $form['table'] = [
          '#type' => 'table',
          '#header' => $header,
          '#empty' => t('Not found'),
      ];

      $form['link_1'] = [
          '#type' => 'operations',
          '#links' => [
              [
                  'title' => $this->t('+ Add Currency'),
                  'url' => $add_url,
              ],
          ],
      ];

      foreach ($response as $data) {

        $edit_url = Url::fromRoute('exchange_service.edit', ['id' => strtolower($data['type'])]);

        $form['table'][] = [
            'currency' => [
                '#plain_text' => $data['currency'],
            ],
            'type' => [
                '#plain_text' => $data['type'],
            ],
            'rate' => [
                '#plain_text' => $data['rate'],
            ],
            'created_at' => [
                '#plain_text' => $data['created_at'],
            ],
            'opt' => [
                '#type' => 'operations',
                '#links' => [
                    [
                        'title' => $this->t('Edit'),
                        'url' => $edit_url,
                    ],
                ],
            ]
        ];
      }
      return $form;
    }

  public function filterResponse($response): array
  {
    $filtered = array_filter($response['rates'], function ($value) {
      return !is_null($value);
    });

    return $filtered;
  }
}