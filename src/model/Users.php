<?php

namespace edwrodrig\usac\model;

class Users {

use UsersRegistration;
use UsersSession;

public $dao;

function __construct() {
  $this->dao = \edwrodrig\usac\Config::get_query_dao();
}

}
