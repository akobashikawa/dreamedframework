<?php
/**
 * Implements hook_root
 */
function nobody_root() {
  $items = array();
  $items['/^.*$/'] = array(
    'action' => 'nobody_default'
  );
  return $items;
}

function nobody_default(&$data) {

  if (is_array_empty($data['res'])) {
    $data['qp']
      ->find(':root title')
      ->html('Dreamed Framework');
    $data['qp']
      ->find(':root body')
      ->append('<h1>Nobody</h1>')
      ->append('<p>Nobody implements action for this request:</p>')
      ->append('<pre>' . print_r($data['req'], TRUE) . '</pre>');
  }

  if ($data['debug']['show'] == 1) {
    $data['qp']
    ->find(':root body')
    ->append('<hr/>' . pr($data['log'], TRUE));
  }

}