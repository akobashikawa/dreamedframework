<?php
/**
 * Implements hook_path
 */
function holamundo_path() {
  $path = array();
  $path['/holamundo$/'] = array(
    'action' => 'holamundo_holamundo'
  );
  return $path;
}

function holamundo_holamundo() {
  echo 'Hola Mundo!';
}
