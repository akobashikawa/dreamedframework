<?php
/**
 * It is executed from index.php
 */
function root_main() {

  // template
  $qp = root_getqp(base_dir() . '/index.html');

  // data
  $data = array();
  $parts = explode('?', $_SERVER['REQUEST_URI']);
  $req = isset($parts[1])?$parts[1]:'';
  $res = array();
  $data['req']['root'] = $req;
  $data['res']['root'] = $res;
  $data['qp'] = $qp;

  // for debug
  root_debug($data);

  // someone?
  hook('root', $data);

  // render
  $data['qp']->writeHTML();

}//root_main

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
  $data['qp']
    ->find(':root title')
    ->html('Dreamed Framework');
  $data['qp']
    ->find(':root body')
    ->append('<h1>Dreamed Framework</h1>');
  return TRUE;
}

/**
 * To show debug info: $data['debug']
 * To disable module: $data['debug']['off']['module'][$module] = 1
 */
function root_debug(&$data) {
  $data['debug']['show'] = 1;
  $data['debug']['off']['hook']['root'] = 0;
  $data['debug']['off']['module']['hola'] = 0;
}