<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 16:07
 */

namespace edwrodrig\usac\query\user;

use edwrodrig\usac\query\user\exception\UserDoesNotExistException;

class SelectUserById extends SelectUser
{
    private $id_user;

    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
SELECT
  id_user,
  name,
  password_hash,
  mail,
  default_lang
FROM
  usac_users
WHERE
  id_user = :id_user
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function where(int $id_user) {
        $this->id_user = $id_user;

        $this
            ->b('id_user', $id_user, \PDO::PARAM_INT);
        return $this;
    }

    /**
     * @throws UserDoesNotExistException
     */
    protected function throw_exception()
    {
        throw new UserDoesNotExistException('id_user', strval($this->id_user));
    }

}