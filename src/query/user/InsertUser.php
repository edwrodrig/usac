<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\user;

use edwrodrig\query\Insert;
use edwrodrig\usac\User;

class InsertUser extends Insert
{
    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
INSERT INTO
  usac_users
(
  name,
  password_hash,
  mail,
  default_lang
)
VALUES
(
  :name,
  :password_hash,
  :mail,
  :default_lang
)
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function set(User $user) {
        $this
            ->b('name', $user->getName())
            ->b('password_hash', $user->getPasswordHash())
            ->b('mail', strval($user->getMail()))
            ->b('default_lang', $user->getDefaultLang());
        return $this;
    }

}