<?php

namespace edwrodrig\usac\model;

use PHPUnit\Framework\TestCase;
use edwrodrig\usac\model\Users;

class UsersRememberedLoginTest extends TestCase {

static public $default_origin = '100.100.100';
static public $default_password = 'password';
static public $default_name = 'edwin';
static public $default_mail = 'edwin@mail.com';
static public $expiration_never = '3000-01-01';


public function setUp() {
  \test\init();
  $this->model = new Users;
}

public function registerUser() {
  $this->model->register(self::$default_name, self::$default_password, self::$default_mail);

  return $this->model->dao->get_user_by_name(self::$default_name)->fetch();
}

public function testLoginUser() {
  $user_data = $this->registerUser();
  $session_data = $this->model->login($user_data['name'], self::$default_password, self::$expiration_never, true, self::$default_origin);

  $this->assertArraySubset([
    'id_user' => $user_data['id_user'],
    'name' => $user_data['name'],
    'expiration' => self::$expiration_never,
    'remembered_login' => []
  ], $session_data);

  $this->assertArrayHasKey('remembered_login', $session_data);
  $this->assertArrayHasKey('id_login', $session_data['remembered_login']);
  $this->assertArrayHasKey('token', $session_data['remembered_login']);

  return $session_data;
}

public function testGetRememberedLoginByIdLogin() {
  $session_data = $this->testLoginUser();
  $login_data = $this->model->get_remembered_login_by_id_login($session_data['remembered_login']['id_login']);

  $this->assertArraySubset([
    'id_login' => $session_data['remembered_login']['id_login'],
    'id_user' => $session_data['id_user'],
    'expiration' => $session_data['expiration'],
    'id_session' => $session_data['id_session'],
    'user' => [
      'id_user' => $session_data['id_user']
    ]
  ], $login_data);

  $this->assertTrue(password_verify($session_data['remembered_login']['token'], $login_data['token_hash']));

  $login_data['session_data'] = $session_data;
  return $login_data;
}

public function testRememberedLogin() {
  $login_data = $this->testGetRememberedLoginByIdLogin();

  $response = $this->model->remembered_login($login_data['id_login'], $login_data['session_data']['remembered_login']['token'], self::$expiration_never, self::$default_origin);

  $this->assertArraySubset([
    'id_user' => $login_data['user']['id_user'],
    'name' => $login_data['user']['name'],
    'expiration' => self::$expiration_never,
    'remembered_login' => [
      'id_login' => $login_data['id_login']
    ]
  ], $response);

  $this->assertArrayHasKey('id_session', $response);
  $this->assertArrayHasKey('token', $response['remembered_login']);
  $this->assertFalse($login_data['id_session'] == $response['id_session']);
  $this->assertFalse($login_data['session_data']['remembered_login']['token'] == $response['remembered_login']['token']);
 
  $this->assertFalse($this->model->dao->get_user_by_id_session($login_data['id_session'])->fetch());
}

public function testCloseRemenberedLogin() {
  $login_data = $this->testGetRememberedLoginByIdLogin();

  $this->model->close_remembered_login($login_data['id_login']);

  $this->assertFalse($this->model->dao->get_user_by_id_session($login_data['id_session'])->fetch());
  $this->assertFalse($this->model->dao->get_remembered_login_by_id_login($login_data['id_login'])->fetch());

}

}
