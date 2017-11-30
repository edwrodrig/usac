<?php

namespace edwrodrig\usac\db\sqlite3;

class Query {

public $pdo;

public function __construct($pdo) {
  $this->pdo = $pdo;
  $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
}

function register_user($name, $password_hash, $mail) {
  $query = <<<'SQL_STMT'
INSERT INTO usac_users (name, password_hash, mail) VALUES (:name, :password_hash, :mail)
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':name', $name);
  $s->bindValue(':password_hash', $password_hash);
  $s->bindValue(':mail', $mail);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $this->pdo->lastInsertId();
}

function get_user_password_by_name($name) {
  $query = <<<'SQL_STMT'
SELECT password_hash FROM usac_users WHERE name = :name
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':name', $name);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function get_user_by_name($name) {
  $query = <<<'SQL_STMT'
SELECT id_user, name, password_hash, mail FROM usac_users WHERE name = :name
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':name', $name);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function get_user_by_mail($mail) {
  $query = <<<'SQL_STMT'
SELECT id_user, name, password_hash, mail FROM usac_users WHERE mail = :mail
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':mail', $mail);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function get_user_by_name_or_mail($name, $mail) {
  $query = <<<'SQL_STMT'
SELECT id_user, name, password_hash, mail FROM usac_users WHERE name = :name OR mail = :mail
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':name', $name);
  $s->bindValue(':mail', $mail);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function get_user_by_id_session($id_session) {
  $query = <<<'SQL_STMT'
SELECT usac_users.id_user, name, password_hash, id_session, login_date, expiration, mail, origin FROM usac_users, usac_sessions WHERE usac_users.id_user = usac_sessions.id_user AND id_session = :id_session
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_session', $id_session);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function change_password_hash_by_id_user($password_hash, $id_user) {
  $query = <<<'SQL_STMT'
UPDATE usac_users SET password_hash = :password_hash WHERE id_user = :id_user
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':password_hash', $password_hash);
  $s->bindValue(':id_user', $id_user);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function change_mail_by_id_user($mail, $id_user) {
  $query = <<<'SQL_STMT'
UPDATE usac_users SET mail = :mail WERE id_user = :id_user
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':mail', $mail);
  $s->bindValue(':id_user', $id_user);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function create_session($id_session, $id_user, $expiration, $origin) {
  $query = <<<'SQL_STMT'
INSERT INTO usac_sessions (id_session, id_user, expiration, origin) VALUES (:id_session, :id_user, :expiration, :origin)
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_session', $id_session);
  $s->bindValue(':id_user', $id_user);
  $s->bindValue(':expiration', $expiration);
  $s->bindValue(':origin', $origin);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $this->pdo->lastInsertId();
}

function close_session_by_id_session($id_session) {
  $query = <<<'SQL_STMT'
DELETE FROM usac_sessions WHERE id_session = :id_session
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_session', $id_session);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function log_session_access($id_session, $id_user, $origin) {
  $query = <<<'SQL_STMT'
INSERT INTO usac_sessions_access (id_session, id_user, origin) VALUES (:id_session, :id_user, :origin)
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_session', $id_session);
  $s->bindValue(':id_user', $id_user);
  $s->bindValue(':origin', $origin);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $this->pdo->lastInsertId();
}

function request_user_registration($id_request, $mail, $origin) {
  $query = <<<'SQL_STMT'
INSERT INTO usac_user_registration_requests (id_request, mail, origin) VALUES (:id_request, :mail, :origin)
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_request', $id_request);
  $s->bindValue(':mail', $mail);
  $s->bindValue(':origin', $origin);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $this->pdo->lastInsertId();
}

function get_user_registration_request_by_id_request($id_request) {
  $query = <<<'SQL_STMT'
SELECT id_request, mail, origin, request_date FROM usac_user_registration_requests WHERE id_request = :id_request
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_request', $id_request);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function clear_user_registration_request_by_id_request($id_request) {
  $query = <<<'SQL_STMT'
DELETE FROM usac_user_registration_requests WHERE id_request = :id_request
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_request', $id_request);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function request_change_user_mail($id_request, $mail, $id_user, $origin) {
  $query = <<<'SQL_STMT'
INSERT INTO usac_change_user_mail_requests (id_request, mail, id_user, origin) VALUES ( :id_request, :mail, :id_user, :origin)
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_request', $id_request);
  $s->bindValue(':mail', $mail);
  $s->bindValue(':id_user', $id_user);
  $s->bindValue(':origin', $origin);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $this->pdo->lastInsertId();
}

function get_user_change_user_mail_request_by_id_request($id_request) {
  $query = <<<'SQL_STMT'
SELECT id_request, mail, id_user, request_date FROM usac_user_change_user_mail_request FROM usac_change_user_mail_request WHERE id_request = :id_request
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_request', $id_request);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

function clear_change_user_mail_request_by_id_request($id_request) {
  $query = <<<'SQL_STMT'
DELETE FROM user_change_user_mail_requests WHERE id_request = :id_request
SQL_STMT;

  $s = $this->pdo->prepare($query);

  $s->bindValue(':id_request', $id_request);

  if ( !$s->execute() ) {
    $e = new \Exception('QUERY_EXECUTION_ERROR');
    $e->description = $s->errorInfo()[2];
    throw $e;
  }
  return $s;
}

}
