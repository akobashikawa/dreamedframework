<?php
/**
 * Implements hook_root
 */
function hello_root() {
  $items = array();
  $items['/helloworld$/'] = array(
    'action' => 'hello_helloworld'
  );
  return $items;
}

/**
 * Callback
 */
function hello_helloworld(&$data) {
  $data['req']['hello'] = 'hello';
  $data['res']['hello']['greeting'] = 'Hello';
  $data['res']['hello']['to'] = 'World';
  hook('hello', $data);
  $greeting = $data['res']['hello']['greeting'];
  $to = $data['res']['hello']['to'];
  $message =  "$greeting $to!";
  $data['qp']
    ->find(':root body')
    ->append('<h1>' . $message . '</h1>');
}
