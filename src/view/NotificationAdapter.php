<?php

namespace edwrodrig\usac\view;

interface NotificationAdapter {

public function registration_requested($id_request, $mail);

public function change_user_mail_requested($id_request, $mail);

}
