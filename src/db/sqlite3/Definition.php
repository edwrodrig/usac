<?php

namespace edwrodrig\usac\db\sqlite3;

class Definition {

public $pdo;

public function __construct($pdo) {
  $this->pdo = $pdo;
  $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
}

function create_table_users() {
  $query = <<<'SQL_STMT'
CREATE TABLE usac_users (id_user INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT UNIQUE NOT NULL, password_hash TEXT NOT NULL, mail TEXT UNIQUE, status INTEGER NOT NULL DEFAULT 0)
SQL_STMT;

  $s = $this->pdo->prepare($query);


  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function create_table_usac_sessions() {
  $query = <<<'SQL_STMT'
CREATE TABLE usac_sessions (id_session TEXT PRIMARY KEY, id_user INTEGER NOT NULL, login_date DATETIME DEFAULT CURRENT_TIMESTAMP, expiration DATETIME, origin TEXT)
SQL_STMT;

  $s = $this->pdo->prepare($query);


  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function create_table_usac_remembered_logins() {
  $query = <<<'SQL_STMT'
CREATE TABLE usac_remembered_logins (id_login TEXT PRIMARY KEY, token_hash TEXT NOT NULL, id_user INTEGER NOT NULL, id_session TEXT, expiration DATETIME)
SQL_STMT;

  $s = $this->pdo->prepare($query);


  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function create_table_usac_session_access() {
  $query = <<<'SQL_STMT'
CREATE TABLE usac_sessions_access (id_session TEXT, id_user NOT NULL, origin TEXT NOT NULL, date DATETIME DEFAULT CURRENT_TIMESTAMP)
SQL_STMT;

  $s = $this->pdo->prepare($query);


  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function create_table_usac_user_registration_requests() {
  $query = <<<'SQL_STMT'
CREATE TABLE usac_user_registration_requests (id_request TEXT PRIMARY_KEY, mail TEXT, request_date DATETIME DEFAULT CURRENT_TIMESTAMP, origin TEXT)
SQL_STMT;

  $s = $this->pdo->prepare($query);


  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function create_table_usac_change_user_mail_requests() {
  $query = <<<'SQL_STMT'
CREATE TABLE usac_change_user_mail_requests (id_request TEXT PRIMARY_KEY, mail TEXT, id_user INTEGER, request_date DATETIME DEFAULT CURRENT_TIMESTAMP, origin TEXT)
SQL_STMT;

  $s = $this->pdo->prepare($query);


  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

static function build_db($pdo) {

  $dao = new self($pdo);

  $dao->create_table_users();
  $dao->create_table_usac_sessions();
  $dao->create_table_usac_remembered_logins();
  $dao->create_table_usac_session_access();
  $dao->create_table_usac_user_registration_requests();
  $dao->create_table_usac_change_user_mail_requests();
}

}
