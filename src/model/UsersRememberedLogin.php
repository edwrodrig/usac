<?php

namespace edwrodrig\usac\model;

trait UsersRememberedLogin {

function remember_login($id_user, $id_session, $expiration) {
  $id_login = \edwrodrig\usac\Util::uniqid();
  $token = \edwrodrig\usac\Util::uniqid();
  $token_hash = password_hash($token, PASSWORD_DEFAULT);
  $this->dao->register_remembered_login($id_login, $token_hash, $id_user, $id_session, $expiration);

  return [
    'id_login' => $id_login,
    'token' => $token
  ];
}

function update_remembered_login($id_login, $id_session, $expiration) {
  $token = \edwrodrig\usac\Util::uniqid();
  $token_hash = password_hash($token, PASSWORD_DEFAULT);
  $this->dao->update_remembered_login_by_id_login($token_hash, $id_session, $expiration, $id_login);

  return [
    'id_login' => $id_login,
    'token' => $token
  ];

}

function close_remembered_login($id_login) {
  $login = $this->get_remembered_login_by_id_login($id_login);
  $this->close_session($login['id_session']);

  $this->dao->close_remembered_login_by_id_login($id_login);
}

function get_remembered_login_by_id_login($id_login) {
  if ( $login = $this->dao->get_remembered_login_by_id_login($id_login)->fetch() ) {
    if ( $user = $this->dao->get_user_by_id_user($login['id_user'])->fetch() ) {
      $login['user'] = $user;
      return $login;
    } else
      throw new \Exception('USER_DOES_NOT_EXISTS');
  } else
    throw new \Exception('REMEMBERED_LOGIN_DOES_NOT_EXISTS');
}

function remembered_login($id_login, $token, $expiration, $origin = null) {
  $origin = \edwrodrig\usac\Util::normalize_origin($origin);

  $login = $this->get_remembered_login_by_id_login($id_login);
  
  if ( !password_verify($token, $login['token_hash']) )
    throw new \Exception('WRONG_PASSWORD');

  $this->close_session($login['id_session']);

  $id_session = $this->create_session($login['user']['id_user'], $expiration, $origin);

  $new_login = $this->update_remembered_login($id_login, $id_session, $expiration);

  return [
    'id_user' => $login['user']['id_user'],
    'name' => $login['user']['name'],
    'expiration' => $expiration,
    'id_session' => $id_session,
    'remembered_login' => $new_login
  ];
}

}
