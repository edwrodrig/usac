<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:38
 */

namespace edwrodrig\usac\request;


use DateInterval;
use edwrodrig\usac\Email;
use edwrodrig\usac\User;

class ChangeMailRequest extends Request
{

    const TABLE = <<<SQL
CREATE TABLE usac_change_mail_requests (
  id_request TEXT NOT NULL PRIMARY KEY,
  mail TEXT NOT NULL,
  creation_date DATETIME NOT NULL,
  expiration_date DATETIME NOT NULL,
  id_user INTEGER NOT NULL
)
SQL;

    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     * @param Email $mail
     * @param DateInterval $duration
     * @return ChangeMailRequest
     * @throws \Exception
     */
    static public function create_from_request(User $user, Email $mail,  DateInterval $duration) : self {
        $request = new self;
        $request->init($duration);
        $request->mail = $mail;
        $request->user = $user;

        return $request;
    }

    static public function create_from_array(array $data) : self {
        $request = new self;
        $request->from_array($data);
        $request->user = $data['user'];
        return $request;
    }

    public function get_user() : User {
        return $this->user;
    }

    public function get_mail() : Email {
        return $this->mail;
    }
}