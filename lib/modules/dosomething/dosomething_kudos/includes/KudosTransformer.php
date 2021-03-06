<?php

module_load_include('php', 'dosomething_api', 'includes/Transformer');

class KudosTransformer extends Transformer {

  /**
   * Display collection of the specified resource.
   *
   * @param array $parameters Filter parameters to limit collection based on specific criteria.
   *  - fid (string|array)
   *  - count (int)
   * @return array
   */
  public function index($parameters) {
    $filters = [
      'fid' => $this->formatData($parameters['reportbackitem_ids']),
      'count' => (int) $parameters['count'],
    ];

    try {
      $kudos = Kudos::find($filters);
      $kudos = services_resource_build_index_list($kudos, 'kudos', 'id');
    }
    catch (Exception $error) {
      return [
        'error' => [
          'message' => $error->getMessage(),
        ],
      ];
    }

    $data = $this->transformCollection($kudos);
    // @TODO: may be better to build an $options array with items...
    $data = dosomething_kudos_sort($data);

    return [
      'kudos' => [
        'data' => $data,
      ]
    ];
  }

  /**
   * Display the specified resource.
   *
   * @param string $id Kudos id.
   * @return array
   */
  public function show($id) {
    try {
      $kudos = Kudos::get($id);
      $kudos = services_resource_build_index_list($kudos, 'kudos', 'id');
    }
    catch (Exception $error) {
      http_response_code('404');
      return [
        'error' => [
          'message' => $error->getMessage(),
        ],
      ];
    }

    return [
      'data' => $this->transform(array_pop($kudos)),
    ];
  }

  /**
   * @param $parameters
   * @return array
   */
  public function create($parameters) {
    global $user;
    $uid = $user->uid;

    if (user_access('view any reportback') && $parameters['northstar_id']) {
      $northstarUser = dosomething_user_get_user_by_northstar_id($parameters['northstar_id']);

      if ($northstarUser) {
        $uid = $northstarUser->uid;
      }
    }

    $values = [
      'fid' => $parameters['fid'],
      'uid' => $uid,
      'created' => time(),
    ];

    foreach($parameters['tids'] as $id) {
      $values['tid'] = $id;

      $record = (new KudosController)->create($values);
      $records[] = $record;
    }

    return $records;
  }

  /**
   * @param $id
   * @return array
   */
  public function delete($id) {
    try {
      $record = (new KudosController)->delete([$id]);
    }
    catch (Exception $error) {
      return [
        'error' => [
          'message' => 'There was an error deleting the specified kudos record.',
        ],
      ];
    }

    if ($record) {
      return [
        'success' => [
          'message' => 'Kudos record successfully deleted.',
        ]
      ];
    }

    return [
      'error' => [
        'message' => 'The specified kudos record id is not valid.',
      ],
    ];
  }

  /**
   * Transform data and build out response.
   *
   * @param object $item Single Kudos object of retrieved data.
   * @return array
   */
  protected function transform($item) {
    $data = [];

    $data += $this->transformKudos($item);

    return $data;
  }

}
