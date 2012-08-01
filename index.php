<?php
/////////
// LIB //
/////////

/**
 * To show preformated value of $x
 */
function pr($x, $toreturn=FALSE) {
  $result = '<pre>' . print_r($x, TRUE) . '</pre>';
  if ($toreturn) {
    return $result;
  }
  echo $result;
}

/**
 * Return array of names of directories in $dir.
 * No included . nor ..
 * Support prefix 'nnn-' for order
 * A key is the full dir name, with order prefix: nnn-ddd
 * A value is the base dir name, without order prefix: ddd
 */
function get_modules_list($dir = 'modules') {
  $dirs = array();
  if ($dh=opendir($dir)) {
    while($file=readdir($dh)) {
      if (is_dir($dir.'/'.$file) && !in_array($file, array('.', '..'))) {
        if ($pos = strpos($file, '-')) {
          $basename = substr($file, $pos+1);
        } else {
          $basename = $file;
        }
        $dirs[$file] = $basename;
      }
    }
    closedir($dh);
  }
  ksort($dirs);
  return $dirs;
}

/**
 * Include module.php inside each module directory
 */
function include_modules() {
  global $modules;

  foreach ($modules as $key => $module) {
    // include
    // it is optional ;-)
    $module_file = 'modules/' . $key . '/module.php';
    if ( file_exists($module_file) ) {
      include_once($module_file);
    }
  }// modules

}

/**
 * Return base_path of url
 */
function base_path() {
  return dirname($_SERVER['SCRIPT_NAME']);
}

/**
 * Return base_path of file
 */
function base_dir() {
  return dirname(__FILE__);
}

require_once 'lib/QueryPath/QueryPath.php';

/**
 * Allow all modules conventional participation
 */
function hook($hook, &$data) {
  global $modules;

  //$data['log']['hook'][$hook] = array();
  $data['log'][] = "hook: $hook";

  if (!isset($data['debug']['off']['hook'][$hook])
      || $data['debug']['off']['hook'][$hook] == 0) {

    foreach ($modules as $key => $module) {
      if (!isset($data['debug']['off']['module'][$module])
        || $data['debug']['off']['module'][$module] == 0) {

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

      }//if module
    }//foreach


  }//if hook
  
}

/**
 * Return querypath based on specified template if exists
 * else return querypath default template
 */
function getqp($template) {
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

function main() {

  // data
  $data = array();
  $parts = explode('?', $_SERVER['REQUEST_URI']);
  $req = isset($parts[1])?$parts[1]:'';
  $res = array();
  $data['req']['root'] = $req;
  $data['res']['root'] = $res;

  // for debug
  debug($data);

  // someone?
  hook('root', $data);

  // render
  if (isset($data['qp'])) {
    $data['qp']->writeHTML();
  }

}//main

/**
 * To show debug info: $data['debug']
 * To disable module: $data['debug']['off']['module'][$module] = 1
 */
function debug(&$data) {
  $data['debug']['show'] = 1;
  $data['debug']['off']['hook']['root'] = 0;
  $data['debug']['off']['module']['hola'] = 0;
}

//////////
// MAIN //
//////////
$modules = get_modules_list('modules');
include_modules();
main();