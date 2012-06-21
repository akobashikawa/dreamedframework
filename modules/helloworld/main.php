<?php
/**
 * Implements hook_path
 */
function helloworld_path() {
  $path = array();
  $path['/helloworld$/'] = array(
    'action' => 'helloworld_helloworld'
  );
  $path['/helloworld2$/'] = array(
    'action' => 'helloworld_helloworld'
  );
  return $path;
}

function helloworld_helloworld() {
  echo 'Hello World!';
}