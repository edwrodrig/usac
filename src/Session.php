<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 14:58
 */

namespace edwrodrig\usac;


use DateInterval;

class Session
{
    const TABLE = <<<SQL
CREATE TABLE usac_sessions (
  id_session TEXT PRIMARY KEY,
  id_user INTEGER NOT NULL,
  creation_date DATETIME,
  expiration_date DATETIME
)
SQL;


    /**
     * @var string
     */
    private $id_session;

    /**
     * @var User
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $creation_date;

    /**
     * @var \DateTime
     */
    private $expiration_date;

    protected function __construct() {}

    /**
     * @param User $user
     * @param DateInterval $duration
     * @return Session
     * @throws \Exception
     */
    public static function create_new_session(User $user, DateInterval $duration) {
        $session = new self;
        $session->user = $user;
        $session->id_session = bin2hex(random_bytes(32));
        $session->creation_date = new \DateTime();
        $session->expiration_date = (clone $session->creation_date)->add($duration);
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

    public function get_id_session() : string
    {
        return $this->id_session;
    }

    public function get_creation_date() : \DateTime
    {
        return $this->creation_date;
    }

    public function get_expiration_date() : \DateTime
    {
        return $this->expiration_date;
    }


}