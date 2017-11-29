<?php

namespace edwrodrig\usac\model;

class Users {

public $dao;

function __construct() {
  $this->dao = \edwrodrig\usac\Config::get_query_dao();
}

function register($name, $password, $mail) {
  try {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $this->dao->register_user($name, $password_hash, $mail);

    if ( $user = $this->dao->get_user_by_name($name)->fetch() ) {
      unset($user['password_hash']);
      return $user; 
    } else {
      throw new \Exception;
    }
  } catch ( \Exception $e ) {
    throw new \Exception('ERROR_AT_REGISTERING_USER', 0, $e);
  }
}

function login($name, $password, $expiration, $origin) {
  if ( $user = $this->dao->get_user_by_name($name)->fetch() ) {

    if ( !password_verify($password, $user['password_hash']) )
      throw new \Exception('WRONG_PASSWORD');

    $id_session = $this->create_session($user['id_user'], $expiration, $origin);
    return [
      'id_user' => $user['id_user'],
      'username' => $name,
      'expiration' => $expiration,
      'id_session' => $id_session
    ];
  } else
    throw new \Exception('USER_DOES_NOT_EXIST');
}

function close_session($id_session) {
  $this->dao->close_session_by_id_session($id_session); 
}

function check_session($id_session, $origin) {
  if ( $user = $this->dao->get_user_by_id_session($id_session)->fetch() ) {
    if ( $user['origin'] != $origin )
      throw new \Exception('DIFFERENT_ORIGIN');

    if ( $user['expiration'] <= date('Y-m-d H:i:s') )
      throw new \Exception('SESSION_EXPIRED');

    unset($user['password_hash']);
        
    return $user;
  } else
    throw new \Exception('INVALID_SESSION');
}

function create_session($id_user, $expiration, $origin) {
  try {
    $this->dao->pdo->beginTransaction();

    $id_session = uniqid("se", true);
    $this->dao->create_session($id_session, $id_user, $expiration, $origin);
    
    $this->dao->log_session_access($id_session, $id_user, $origin);
 
    $this->dao->pdo->commit();
    return $id_session;
  } catch ( \Exception $e ) {
    $this->dao->pdo->rollBack();
    throw new \Exception('ERROR_CREATING_SESSION', 0, $e);
  }
}

static function current_session_expiration_date() {
  $expiration = new \DateTime("now", new \DateTimeZone('GMT'));
  $expiration->add(new \DateInterval('PT' . \edwrodrig\usac\Config::$default_session_duration . 'S'));
  return $expiration->format('Y-m-d H:i:s');
}


}
