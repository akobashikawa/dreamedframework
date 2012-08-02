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
  // template
  $data['qp'] = getqp('helloworld.html');

  $data['req']['hello'] = 'hello';
  $data['res']['hello']['greeting'] = 'Hello';
  $data['res']['hello']['to'] = 'World';
  hook('hello', $data);
  $greeting = $data['res']['hello']['greeting'];
  $to = $data['res']['hello']['to'];
  $message =  "$greeting $to!";
  $data['qp']
    ->find(':root body')
    ->before('<h1>' . $message . '</h1>');
}
