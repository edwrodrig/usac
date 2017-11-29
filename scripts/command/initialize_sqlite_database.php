<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . '../../src/usac/controller' . DIRECTORY_SEPARATOR . 'Command.php');

try {

  $params = getopt('i::o::', 
array (
  0 => 'file::',
)
  );
  $output_file = $params['o'] ?? null;

  if ( file_exists($params['i'] ?? '') ) {
    $content = file_get_contents($params['i']);
    
    $content = json_decode($content, true);
    
    if ( is_array($content) ) {
      $params  = $content;
    }
    
  }

  $args = [];

  if ( !isset($params["file"]) ) {
    throw new \Exception('UNDEFINED_ARGUMENT');
  } else {
    $param = $params['file'];
  }

  $type = gettype($param);
  if ( $type != "string" )
    throw new \Exception('WRONG_ARGUMENT_TYPE');

  $args[] = $param;


  $service = new \usac\controller\Command();
  $return = $service->initialize_sqlite_database(...$args);


  if ( !empty($output_file) ) 
    file_put_contents(
      $output_file,
      json_encode($return, JSON_PRETTY_PRINT)
    );

  else
    echo json_encode($return, JSON_PRETTY_PRINT);

} catch ( \Exception $e ) {
  error_log('ERROR : ' . $e->getMessage());
  error_log("\nUsage :\n  php " . basename(__FILE__) . ' --file=value');
  exit(1);
}
