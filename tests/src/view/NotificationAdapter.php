<?php

namespace test\view;

class NotificationAdapter implements \edwrodrig\usac\view\NotificationAdapter {

public $id_request;
public $mail;

public function registration_requested($id_request, $mail) {
  $this->id_request = $id_request;
  $this->mail = $mail;
}

public function change_user_mail_requested($id_request, $mail) {
  $this->id_request = $id_request;
  $this->mail = $mail;
}


}
