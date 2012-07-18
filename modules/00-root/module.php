<?php
require_once 'QueryPath/QueryPath.php';

function root_main() {

  $parts = explode('?', $_SERVER['REQUEST_URI']);
  $req = isset($parts[1])?$parts[1]:'';
  $res = array();
  $qp = root_getqp(base_dir() . '/index.html');
  $data = array();
  $data['req']['root'] = $req;
  $data['res']['root'] = $res;
  $data['qp'] = $qp;

  root_hook('root', $data);

  $data['qp']->writeHTML();

}//root_main

/**
 * Allow all modules conventional participation
 */
function root_hook($hook, &$data) {
  global $modules;

  foreach ($modules as $key => $module) {
    $f = $module . '_' . $hook;
    $result = array();
    if ( function_exists($f) ) {
      $items = $f();
      foreach ($items as $pattern => $item) {
        if (preg_match($pattern, $data['req'][$hook])) {
          $action = $item['action'];
          $result = $action($data);
        }//if
      }//foreach
    }//if
    $data['res'][$hook][$module] = $result;
  }//foreach
}

/**
 * Return querypath based on specified template if exists
 * else return querypath default template
 */
function root_getqp($template) {
  if (!empty($template) && file_exists($template)) {
    $qp = htmlqp($template);
  } else {
    $template = '';
    $qp = qp(QueryPath::HTML_STUB);
  }
  return $qp;
}

/**
 * Implements hook_root
 */
function root_root() {
  $items = array();
  $items['/^$/'] = array(
    'action' => 'root_default'
  );
  $items['/^.*$/'] = array(
    'action' => 'root_nobody'
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
}
/**
 * Callback
 */
function root_nobody(&$data) {
  if ($data['res']==array('root'=>array())) {
    $data['qp']
      ->find(':root title')
      ->html('Dreamed Framework');
    $data['qp']
      ->find(':root body')
      ->append('<p>nobody implements action for this request:</p>')
      ->append('<pre>' . print_r($data['req'], TRUE) . '</pre>');
  }
}