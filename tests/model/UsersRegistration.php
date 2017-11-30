<?php

namespace edwrodrig\usac\model;

use PHPUnit\Framework\TestCase;
use edwrodrig\usac\model\Users;

class UsersRegistrationTest extends TestCase {

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

public function testRequestUserRegistration() {
  $data = $this->model->request_user_registration('edwin@mail.com', '1.1.1.1');

  $adapter = \edwrodrig\usac\Config::get_registration_adapter();
  
  $this->assertEquals($adapter->mail, 'edwin@mail.com');

  $user = $this->model->confirm_registration($adapter->id_request, 'edwin', 'pass', '1.1.1.1');
  $this->assertArraySubset([
      'id_user' => 1,
      'name' => 'edwin',
      'mail' => 'edwin@mail.com'
    ],
    $user
  );  

}

}
