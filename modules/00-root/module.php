<?php
/**
 * Implements hook_root
 */
function root_root() {
  $items = array();
  $items['/^$/'] = array(
    'action' => 'root_default'
  );
  return $items;
}

/**
 * Callback
 */
function root_default(&$data) {
  // template
  $data['qp'] = getqp(base_dir() . '/index.html');

  $data['qp']
    ->find(':root title')
    ->html('Dreamed Framework');
  $data['qp']
    ->find(':root body')
    ->append('<h1>Dreamed Framework</h1>');
  return TRUE;
}
