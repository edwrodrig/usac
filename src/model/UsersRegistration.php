<?php

namespace edwrodrig\usac\model;

trait UsersRegistration {

function register($name, $password, $mail) {
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  $this->dao->register_user($name, $password_hash, $mail);

  if ( $user = $this->dao->get_user_by_name($name)->fetch() ) {
    unset($user['password_hash']);
    return $user; 
  } else {
    throw new \Exception;
  }
}


function request_user_registration(string $mail, $origin) {
  $id_request = uniqid();
  $this->dao->request_user_registration($id_request, $mail, $origin);

  $adapter = \edwrodrig\usac\Config::get_notification_adapter();
  $adapter->registration_requested($id_request, $mail);

  $data = [
    'id_request' => $id_request,
    'mail' => $mail,
  ];

  return $data;
}

function confirm_registration($id_request, $name, $password, $origin) {
  if ( $request = $this->dao->get_user_registration_request_by_id_request($id_request)->fetch() ) {
    $user = $this->register($name, $password, $request['mail']);
    $this->dao->clear_user_registration_request_by_id_request($id_request);
    return $user;
  } else {
    throw new \Exception('USER_REGISTRATION_REQUEST_DOES_NOT_EXIST');
  }

}

}
