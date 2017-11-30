<?php

namespace edwrodrig\usac\model;

trait UsersSession {

function login($name, $password, $expiration, $origin) {
  if ( $user = $this->dao->get_user_by_name($name)->fetch() ) {

    if ( !password_verify($password, $user['password_hash']) )
      throw new \Exception('WRONG_PASSWORD');

    $id_session = $this->create_session($user['id_user'], $expiration, $origin);
    return [
      'id_user' => $user['id_user'],
      'name' => $name,
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
        
    return $user;
  } else
    throw new \Exception('INVALID_SESSION');
}

function check_session_and_password($id_session, $password, $origin) {
  $user = $this->check_session($id_session, $origin);

  if ( !password_verify($password, $user['password_hash']) ) {
    throw new \Exception('WRONG_PASSWORD');
  }
  return $user;
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
    throw $e;
  }
}

static function current_session_expiration_date() {
  $expiration = \edwrodrig\usac\Utils::future_date(\edwrodrig\usac\Config::$default_session_duration);
  return $expiration->format('Y-m-d H:i:s');
}


}
