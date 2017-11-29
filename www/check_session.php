<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . '../src/usac/controller' . DIRECTORY_SEPARATOR . 'Service.php');

try {

  $params = $_GET;

  $args = [];

  if ( !isset($params["id_session"]) ) {
    throw new \Exception('UNDEFINED_ARGUMENT');
  } else {
    $param = $params['id_session'];
  }

  $type = gettype($param);
  if ( $type != "string" )
    throw new \Exception('WRONG_ARGUMENT_TYPE');

  $args[] = $param;


  $service = new \usac\controller\Service();
  $return = $service->check_session(...$args);

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
