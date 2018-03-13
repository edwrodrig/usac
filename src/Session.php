<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 14:58
 */

namespace edwrodrig\usac;


class Session
{
    const TABLE = <<<SQL
CREATE TABLE usac_sessions (
  id_session TEXT PRIMARY KEY,
  id_user INTEGER NOT NULL,
  creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  expiration_date DATETIME,
)
SQL;


    private $id_session;

    /**
     * @var User
     */
    private $user;

    private $creation_date;

    /**
     * @var \DateTime
     */
    private $expiration_date;

    protected function __construct() {}

    /**
     * @param User $user
     * @return Session
     * @throws \Exception
     */
    public static function create_new_session(User $user) {
        $session = new self;
        $session->user = $user;
        $session->id_session = bin2hex(random_bytes(32));
        $session->creation_date = new \DateTime();
        $session->expiration_date = new \DateTime();
        return $session;
    }

    public static function create_from_array(array $data) {
        $session = new self;
        $session->user = $data['user'];
        $session->id_session = $data['id_session'];
        $session->creation_date = $data['creation_date'];
        $session->expiration_date = $data['expiration_date'];
        return $session;
    }

    public function get_user() : User {
        return $this->user;
    }

    public function is_expired() : bool {
        return $this->expiration_date < new \DateTime;
    }


}