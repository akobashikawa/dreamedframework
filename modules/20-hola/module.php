<?php
/**
 * Implements hook_hello
 */
function hola_hello() {
  $items = array();
  $items['/^helloworld$/'] = array(
    'action' => 'hola_hola'
  );
  return $items;
}

/**
 * Callback
 */
function hola_hola(&$data) {
  $data['res']['hello']['greeting'] = 'Hola';
  $data['res']['hello']['to'] = 'Mundo';
}