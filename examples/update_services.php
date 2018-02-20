<?php

require_once(__DIR__ . '/../vendor/autoload.php');

edwrodrig\generator_service\Utils::generate_from_source(
  \edwrodrig\usac\Config::SERVICE_DIR . '/Services.php',
  [
    'input_type' => 'get',
    'output_type' => 'json',
    'output_dir' => __DIR__ . '/ws',
    'source' => '../../vendor/autoload.php'
  ]
);

