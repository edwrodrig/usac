<?php

namespace edwrodrig\usac;

class Config {

public static $pdo;
public static $engine = 'sqlite3';

//session duration in seconds
public static $default_session_duration = 3600; 

public static function create_database() {
  $class_name = '\edwrodrig\usac\db\\' . self::$engine . '\\Definition';
  {$class_name}::build_db(self::$pdo);
}

public static function get_query_dao() {
  static $dao = new {'\edwrodrig\usac\db\\' . self::$engine . '\\Query'}(self::$pdo);
  return $dao;
}

public static function test_database() {
  self::$pdo = new \PDO('sqlite::memory:');
  self::$engine = 'sqlite3';
  self::create_database();
}

}
