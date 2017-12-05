<?php

require_once(__DIR__ . '/../vendor/autoload.php');

\edwrodrig\generate_service\Utils::generator_from_source(
  __DIR__ . '/../src/usac/controller/Service.php',
  [
    'input_type' => 'get',
    'output_type' => 'json',
    'output_dir' => __DIR__ . '/../www'
  ]
);
