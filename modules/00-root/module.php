<?php
// LIBRARY BEGIN

require_once 'QueryPath/QueryPath.php';

/**
 * Allow all modules conventional participation
 */
function root_hook($hook, &$data) {
  global $modules;

  //$data['log']['hook'][$hook] = array();
  $data['log'][] = "hook: $hook";
  foreach ($modules as $key => $module) {
    $f = $module . '_' . $hook;
    $result = array();
    if ( function_exists($f) ) {
      $items = $f();
      foreach ($items as $pattern => $item) {
        if (preg_match($pattern, $data['req'][$hook])) {
          $action = $item['action'];
          //$data['log']['hook'][$hook]['module'][$module]['pattern'][$pattern][] = $action;
          $data['log'][] = "hook: $hook, module: $module, pattern: $pattern, action: $action";
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
 * Return true if $x and descendants is empty
 * else return FALSE
 */
function is_array_empty($x) {
  if (is_array($x) && count($x)>0) {
    foreach ($x as $item) {
      if (!is_array_empty($item)) {
        return FALSE;
      }
    }
    return TRUE;
  } else {
    return empty($x);
  }
}

// LIBRARY END

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

  // someone?
  root_hook('root', $data);

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
