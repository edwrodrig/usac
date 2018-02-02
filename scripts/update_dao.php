<?php

require_once(__DIR__ . '/../vendor/autoload.php');

\edwrodrig\generator_dao\Generator::generate([
  'files' => [__DIR__ . '/../files/db/query'],
  'class_name' => '\edwrodrig\usac\db\sqlite3\Query',
  'engine' => 'sqlite3',
  'output_file' => __DIR__ . '/../src/db/sqlite3/Query.php'
]);

\edwrodrig\generator_dao\Generator::generate([
  'files' => [__DIR__ . '/../files/db/definition.json'],
  'class_name' => '\edwrodrig\usac\db\sqlite3\Definition',
  'engine' => 'sqlite3',
  'output_file' => __DIR__ . '/../src/db/sqlite3/Definition.php'
]);

