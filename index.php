<?php
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

//////////
// MAIN //
//////////
$modules = get_modules_list('modules');
include_modules();
root_main();