<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:19
 */

namespace edwrodrig\usac\request;

use DateInterval;
use edwrodrig\usac\Email;

class RegistrationRequest extends Request
{
    const TABLE = <<<SQL
CREATE TABLE usac_registration_requests (
  id_request TEXT NOT NULL PRIMARY KEY,
  mail TEXT NOT NULL,
  creation_date DATETIME NOT NULL,
  expiration_date DATETIME NOT NULL
)
SQL;


    /**
     * @param Email $mail
     * @param DateInterval $duration
     * @return RegistrationRequest
     * @throws \Exception
     */
    static public function create_from_request(Email $mail, DateInterval $duration) {
        $request = new self;
        $request->init($duration);
        $request->mail = $mail;

        return $request;
    }

    static public function create_from_array(array $data) : self {
        $request = new self;
        $request->from_array($data);
        return $request;
    }

    public function get_mail() : Email {
        return $this->mail;
    }

}