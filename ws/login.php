<?php

require_once(__DIR__ . '/../vendor/autoload.php');

try {

  $params = $_GET;

  $args = [];

  if ( !isset($params["name"]) ) {
    throw new \Exception('UNDEFINED_ARGUMENT');
  } else {
    $param = $params['name'];
  }

  $type = gettype($param);
  if ( $type != "string" )
    throw new \Exception('WRONG_ARGUMENT_TYPE');

  $args[] = $param;


  if ( !isset($params["password"]) ) {
    throw new \Exception('UNDEFINED_ARGUMENT');
  } else {
    $param = $params['password'];
  }

  $type = gettype($param);
  if ( $type != "string" )
    throw new \Exception('WRONG_ARGUMENT_TYPE');

  $args[] = $param;


  $return = (new \edwrodrig\usac\controller\Services)->login(...$args);
  $return = [
    'status' => 0,
    'data' => $return
  ];

  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($return);

} catch ( \Exception $e ) {

  $return = [
    'status' => -1,
    'message' => $e->getMessage()
  ];

  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($return);
}
