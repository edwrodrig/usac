<?php

namespace edwrodrig\usac\model;

use PHPUnit\Framework\TestCase;
use edwrodrig\usac\model\Users;

class UsersRegistrationTest extends TestCase {

public static $default_name = 'edwin';
public static $default_password = 'password';
public static $default_mail = 'edwin@mail.com';
public static $default_origin = '1.1.1.1';

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

public function testRequestUserRegistration() {
  $data = $this->model->request_user_registration(self::$default_mail, self::$default_origin);

  $adapter = \edwrodrig\usac\Config::get_notification_adapter();
  
  $this->assertEquals($adapter->mail, self::$default_mail);

  $user = $this->model->confirm_registration($adapter->id_request, self::$default_name, self::$default_password, self::$default_origin);
  $this->assertArraySubset([
      'id_user' => 1,
      'name' => self::$default_name,
      'mail' => self::$default_mail
    ],
    $user
  );  
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage USER_REGISTRATION_REQUEST_DOES_NOT_EXIST
 */
public function testRequestNotExists() {
  $this->model->confirm_registration('not_existant', self::$default_name, self::$default_password, self::$default_origin);

}

}
