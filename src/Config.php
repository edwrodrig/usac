<?php

namespace edwrodrig\usac;

class Config {

public static $pdo;
public static $notification_adapter;
public static $engine = 'sqlite3';

//session duration in seconds
public static $default_session_duration = 3600; 

public static function create_database() {
  $class_name = '\edwrodrig\usac\db\\' . self::$engine . '\\Definition';
  $class_name::build_db(self::$pdo);
}

public static function get_query_dao() {
  $class_name = '\edwrodrig\usac\db\\' . self::$engine . '\\Query';
  $dao = new $class_name(self::$pdo);
  return $dao;
}

public static function test_database() {
  self::$pdo = new \PDO('sqlite::memory:');
  self::$engine = 'sqlite3';
  self::create_database();
}

public static function get_notification_adapter() {
  return self::$notification_adapter;  
}

}
