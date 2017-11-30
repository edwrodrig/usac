<?php

namespace edwrodrig\usac\model;

trait UsersChangeMailProcess {

function request_change_user_mail($id_user, $mail, $origin) {
  $id_request = uniqid();
  $this->dao->request_change_user_mail($id_request, $mail, $id_user, $origin);

  $adapter = \edwrodrig\usac\Config::get_notification_adapter();
  $adapter->change_user_mail_requested($id_request, $mail);

  $data = [
    'id_request' => $id_request,
    'mail' => $mail,
  ];

  return $data;
}

function confirm_user_mail_change($id_request, $origin) {
  if ( $request = $this->dao->get_change_user_mail_request_by_id_request($id_request)->fetch() ) {
    $this->dao->change_mail_by_id_user($request['mail'], $request['id_user']);
    $this->dao->clear_change_user_mail_request_by_id_request($id_request);
  } else {
    throw new \Exception('CHANGE_USER_MAIL_REQUEST_DOES_NOT_EXIST');
  }

}

}
