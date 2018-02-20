<?php

namespace edwrodrig\usac;

class Config {

public static $pdo;
public static $notification_adapter;
public static $engine = 'sqlite3';

const SERVICE_DIR = __DIR__ . '/controller';

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

public static function get_notification_adapter() {
  return self::$notification_adapter;  
}

}
