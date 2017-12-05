<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$builder = new edwrodrig\generator_service\Builder([
  'input_type' => 'get',
  'output_type' => 'json',
  'output_dir' => __DIR__ . '/../ws',
  'source' => '../vendor/autoload.php'
]);

$builder->add(__DIR__ . '/../src/controller/Services.php');
$builder->generate_specification();

