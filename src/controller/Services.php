<?php
namespace edwrodrig\usac\controller;

class Services {

public $users;

public function __construct() {
  $this->users = new \edwrodrig\usac\model\Users;
}

public function register(string $name, string $password, string $mail) {
  return $this->users->register($name, $password, $mail);
}

public function login(string $name, string $password) {
  $expiration_date = \edwrodrig\usac\model\Users::current_session_expiration_date();

  return $this->users->login($name, $password, $expiration_date);
}

public function close_session(string $id_session) {
  return $this->users->close_session($id_session);
}

public function check_session(string $id_session) {
  $session_info = $this->users->check_session($id_session);
  unset($session_info['password_hash']);
  return $session_info;
}

};
