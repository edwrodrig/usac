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

class UpdateUserPasswordHash extends Update
{

    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
UPDATE
  usac_users
SET
  password_hash = :password_hash
WHERE
  id_user = :id_user
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function set(User $user) {
        $this
            ->b('password_hash', $user->getPasswordHash())
            ->b('id_user', $user->get_id_user(), \PDO::PARAM_INT);
        return $this;
    }
}