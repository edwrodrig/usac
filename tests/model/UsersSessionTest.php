<?php

namespace edwrodrig\usac\model;

use PHPUnit\Framework\TestCase;
use edwrodrig\usac\model\Users;

class UsersSessionTest extends TestCase {

public function setUp() {
  \test\init();
  $this->model = new Users;
}

public function testRegisterUser() {
  $name = 'edwin';
  $this->model->register($name, 'password', 'edwin@mail.com');

  if ( $user = $this->model->dao->get_user_by_name($name)->fetch() ) {
    $this->assertArraySubset([
      'id_user' => '1',
      'name' => $name,
      'mail' => 'edwin@mail.com'
    ], $user);
    return $user;
  } else {
    $this->assertTrue(false, 'User must exists');
  }
}

public function testLoginUser() {
  $expiration = '3000-01-01';
  $origin ='100.100.100.100';
  $user_data = $this->testRegisterUser();
  $id_session = $this->model->create_session($user_data['id_user'], $expiration, $origin);

  $user = $this->model->check_session($id_session, $origin);
  $this->assertArraySubset([
    'id_user' => $user_data['id_user'],
    'name' => $user_data['name'],
    'id_session' => $id_session,
    'expiration' => $expiration,
    'mail' => $user_data['mail'],
    'origin' => $origin
  ], $user);

  return $user;
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage DIFFERENT_ORIGIN
 */
public function testDifferentOrigin() {
  $expiration = '3000-01-01';
  $origin ='100.100.100.100';

  $user_data = $this->testRegisterUser();
  $id_session = $this->model->create_session($user_data['id_user'], $expiration, $origin);

  $user = $this->model->check_session($id_session, $origin . '.100');
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage SESSION_EXPIRED
 */
public function testSessionExpired() {
  $expiration = '1900-01-01';
  $origin ='100.100.100.100';

  $user_data = $this->testRegisterUser();
  $id_session = $this->model->create_session($user_data['id_user'], $expiration, $origin);

  $user = $this->model->check_session($id_session, $origin);
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage INVALID_SESSION
 */
public function testInvalidSession() {
  $this->model->check_session('wachulin', '');
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage INVALID_SESSION
 */
public function testCloseSession() {
  $user_data = $this->testLoginUser();

  $this->model->close_session($user_data['id_session']);
  $this->model->check_session($user_data['id_session'], $user_data['origin']);
}

}
