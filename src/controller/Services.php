<?php

namespace usac\controller;

class Services {

public $model;

public function __construct() {
  $this->model = new \usac\model\User;
}

public function register(string $name, string $password, string $mail) {
  return $this->model->register($name, $password, $mail);
}

public function login(string $name, string $password) {
  $expiration_date = \usac\model\Users::current_session_expiration_date();

  return $this->model->login($name, $password, $expiration_date, $this->get_origin());
}

public function close_session(string $id_session) {
  return $this->model->close_session($id_session);
}

public function check_session(string $id_session) {
  $session_info = $this->model->check_session($id_session, $this->get_origin());
  unset($session_info['password_hash']);
  return $session_info;
}

public function request_user_registration(string $mail) {

}

public function request_change_user_name(string $id_session, string $password) {
  $this->check_session($id_session);
}

public function request_change_user_mail(string $id_session, string $password) {

}

public function request_change_user_password(string $name = '', string $mail = '') {

}

public function change_user_mail(string $id_request) {

}

public function change_user_password(string $id_session, string $password, string $new_password) {

}

public function confirm_registration(

private function get_origin() {
  return $_SERVER['REMOVE_HOST'] ?? 'local';
}


};
