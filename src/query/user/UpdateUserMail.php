<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 19:27
 */

namespace edwrodrig\usac\query\user;

use edwrodrig\query\Update;
use edwrodrig\usac\User;

class UpdateUserMail extends Update
{

    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
UPDATE
  usac_users
SET
  mail = :mail
WHERE
  id_user = :id_user
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function set(User $user) {
        $this
            ->b('mail', strval($user->get_mail()))
            ->b('id_user', $user->get_id_user(), \PDO::PARAM_INT);
        return $this;
    }
}