<?php

namespace edwrodrig\usac\model;

use PHPUnit\Framework\TestCase;
use edwrodrig\usac\model\Users;

class UsersChangeMailProcessTest extends TestCase {

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

public function testRequestChangeUserMail() {
  $new_mail = 'new@mail.com';
  $user_data = $this->testRegisterUser();
  $data = $this->model->request_change_user_mail($user_data['id_user'], $new_mail, self::$default_origin);

  $adapter = \edwrodrig\usac\Config::get_notification_adapter();
  
  $this->assertEquals($adapter->mail, $new_mail);

  $this->model->confirm_user_mail_change($adapter->id_request, self::$default_origin);

  if ( $user = $this->model->dao->get_user_by_name(self::$default_name)->fetch() ) {
    $this->assertArraySubset([
      'id_user' => 1,
      'name' => self::$default_name,
      'mail' => $new_mail
    ],$user);
  } else {
    $this->assertTrue(false, 'User must exists');
  }
}

/**
 * @expectedException Exception
 * @expectedExceptionMessage CHANGE_USER_MAIL_REQUEST_DOES_NOT_EXIST
 */
public function testRequestNotExists() {
  $this->model->confirm_user_mail_change('not_existant', self::$default_origin);

}

}
