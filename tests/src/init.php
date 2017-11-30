<?php

namespace test;

function init() {
  \edwrodrig\usac\Config::$pdo = new \PDO('sqlite::memory:');
  \edwrodrig\usac\Config::$engine = 'sqlite3';
  \edwrodrig\usac\Config::create_database();

  \edwrodrig\usac\Config::$notification_adapter = new \test\view\NotificationAdapter;
}
