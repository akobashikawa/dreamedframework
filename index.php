<?php
function pr($x) {
  echo '<pre>' . print_r($x, TRUE) . '</pre>';
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

function process() {
  $url = $_SERVER['REQUEST_URI'];
  $modules = get_modules_list('modules');pr($modules);
  $paths = array();

  foreach ($modules as $module) {
    // include
    $module_file = 'modules/' . $module . '/main.php';
    if ( file_exists($module_file) ) {
      include_once($module_file);
    }

    // register path
    $f = $module . '_path';
    if (function_exists($f)) {
      $paths[$module] = $f();
    }
  }

  foreach ($paths as $path) {
    //pr($path);
    foreach ($path as $key => $value) {
      //pr($key);
      if ( preg_match($key, $url) == 1 ) {
        $value['action']();
      }
    }
  }

}

process();