<?php

namespace usac\controller;

class Command {

public function initialize_sqlite_database(string $file) {
  \usac\Config::$pdo = new \PDO('sqlite:' . $file);
  \usac\Config::create_database();
}

}
