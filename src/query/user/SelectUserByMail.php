<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 16:07
 */

namespace edwrodrig\usac\query\user;

use edwrodrig\usac\Email;
use edwrodrig\usac\query\user\exception\UserDoesNotExistException;

class SelectUserByMail extends SelectUser
{
    private $mail;

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
  mail = :mail
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function where(Email $mail) {
        $this->mail = $mail;

        $this
            ->b('mail', strval($mail));
        return $this;
    }

    /**
     * @throws UserDoesNotExistException
     */
    protected function throw_exception()
    {
        throw new UserDoesNotExistException('mail', strval($this->mail));
    }


}