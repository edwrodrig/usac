<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 16:07
 */

namespace edwrodrig\usac\query\user;

use edwrodrig\usac\query\user\exception\UserDoesNotExistException;

class SelectUserByName extends SelectUser
{
    private $name;

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
  name = :name
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function where(string $name) {
        $this->name = $name;

        $this
            ->b('name', $name);
        return $this;
    }

    /**
     * @throws UserDoesNotExistException
     */
    protected function throw_exception()
    {
        throw new UserDoesNotExistException('name', $this->name);
    }

}