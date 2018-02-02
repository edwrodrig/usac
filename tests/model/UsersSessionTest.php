<?php

namespace edwrodrig\usac\model;

use PHPUnit\Framework\TestCase;
use edwrodrig\usac\model\Users;

class UsersSessionTest extends TestCase {

static public $default_origin = '100.100.100';
static public $default_password = 'password';
static public $default_name = 'edwin';
static public $default_mail = 'edwin@mail.com';
static public $expiration_never = '3000-01-01';


public function setUp() {
  \test\init();
  $this->model = new Users;
}

public function testRegisterUser() {
  $this->model->register(self::$default_name, self::$default_password, self::$default_mail);

  if ( $user = $this->model->dao->get_user_by_name(self::$default_name)->fetch() ) {
    $this->assertArraySubset([
      'id_user' => '1',
      'name' => self::$default_name,
      'mail' => self::$default_mail
    ], $user);
    return $user;
  } else {
    $this->assertTrue(false, 'User must exists');
  }
}

public function testCreateSession() {
  $user_data = $this->testRegisterUser();
  $id_session = $this->model->create_session($user_data['id_user'], self::$expiration_never, self::$default_origin);

  $user = $this->model->check_session($id_session, self::$default_origin);
  $this->assertArraySubset([
    'id_user' => $user_data['id_user'],
    'name' => $user_data['name'],
    'id_session' => $id_session,
    'expiration' => self::$expiration_never,
    'mail' => $user_data['mail'],
    'origin' => self::$default_origin
  ], $user);

  return $user;
}

public function testLoginUser() {
  $user_data = $this->testRegisterUser();
  $session_data = $this->model->login($user_data['name'], self::$default_password, self::$expiration_never, false, self::$default_origin);

  $this->assertArraySubset([
    'id_user' => $user_data['id_user'],
    'name' => $user_data['name'],
    'expiration' => self::$expiration_never,
  ], $session_data);

  return $session_data;
}

public function testCheckSession() {
  $user_data = $this->testLoginUser();
  $session_data = $this->model->check_session($user_data['id_session'], self::$default_origin);

  $this->assertArraySubset([
    'id_user' => $user_data['id_user'],
    'name' => $user_data['name'],
    'id_session' => $user_data['id_session'],
    'expiration' => self::$expiration_never,
    'origin' => self::$default_origin
  ], $session_data);
}

public function testCheckSessionByPassword() {
  $user_data = $this->testLoginUser();
  $session_data = $this->model->check_session_and_password($user_data['id_session'], self::$default_password, self::$default_origin);

  $this->assertArraySubset([
    'id_user' => $user_data['id_user'],
    'name' => $user_data['name'],
    'id_session' => $user_data['id_session'],
    'expiration' => self::$expiration_never,
    'origin' => self::$default_origin
  ], $session_data);
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage WRONG_PASSWORD
 */
public function testCheckSessionWrongPassword() {
  $user_data = $this->testLoginUser();
  $session_data = $this->model->check_session_and_password($user_data['id_session'], 'wrong_password', self::$default_origin);
}


/**
 * @expectedException Exception
 * @expectedExceptionMessage USER_DOES_NOT_EXIST
 */
public function testLoginUserNotExists() {
  $this->model->login('not_exists', self::$default_password, self::$expiration_never, self::$default_origin);
  
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage WRONG_PASSWORD
 */
public function testLoginUserWrongPassword() {
  $user_data = $this->testRegisterUser();
  $session_data = $this->model->login($user_data['name'], 'wrong_password', self::$expiration_never, self::$default_origin);
}


/**
 * @expectedException Exception
 * @expectedExceptionMessage DIFFERENT_ORIGIN
 */
public function testDifferentOrigin() {
  $session_data = $this->testLoginUser();

  $user = $this->model->check_session($session_data['id_session'], 'other_origin');
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage SESSION_EXPIRED
 */
public function testSessionExpired() {
  $user_data = $this->testRegisterUser();
  $id_session = $this->model->create_session($user_data['id_user'], '1900-01-01', self::$default_origin);

  $user = $this->model->check_session($id_session, self::$default_origin);
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage INVALID_SESSION
 */
public function testInvalidSession() {
  $this->model->check_session('wachulin', self::$default_origin);
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage INVALID_SESSION
 */
public function testCloseSession() {
  $user_data = $this->testLoginUser();

  $this->model->close_session($user_data['id_session']);
  $this->model->check_session($user_data['id_session'], self::$default_origin);
}

}
