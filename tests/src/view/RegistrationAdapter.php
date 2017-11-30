<?php

namespace test\view;

class RegistrationAdapter extends \edwrodrig\usac\view\RegistrationAdapter {

public $id_request;
public $mail;

public function registration_requested($id_request, $mail) {
  $this->id_request = $id_request;
  $this->mail = $mail;
}

public function change_user_mail_requested($id_request, $user_data, $new_mail) {
  $this->id_request;
  $this->mail = $mail;
  $this->user_data = $user_data;
}

}
