<?php
declare(strict_types=1);

namespace edwrodrig\usac;

use DateInterval;
use DateTime;

class Session
{
    /**
     * @var SessionId
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var DateTime
     */
    private $creation_date;

    /**
     * @var DateTime
     */
    private $expiration_date;

    protected function __construct() {}

    /**
     * Creates a new session for user.
     *
     * It generates a new random {@see SessionId session id}
     * @param User $user
     * @param DateInterval $duration
     * @return Session
     * @throws \Exception
     */
    public static function createNewSession(User $user, DateInterval $duration) {
        $session = new self;
        $session->user = $user;
        $session->id = SessionId::createNew();

        $session->creation_date = new DateTime();
        $session->expiration_date = (clone $session->creation_date)->add($duration);
        return $session;
    }

    public static function createFromArray(array $data) {
        $session = new self;
        $session->user = $data['user'];
        $session->id = new SessionId($data['id_session']);
        $session->creation_date = $data['creation_date'];
        $session->expiration_date = $data['expiration_date'];
        return $session;
    }

    /**
     * Get the user relative to this session
     * @return User
     */
    public function getUser() : User {
        return $this->user;
    }

    /**
     * Check if this session is expired
     *
     * It compares the {@see Session::getExpirationDate() expiration date} with some now date, by default system date
     * @param DateTime|null $now
     * @return bool
     */
    public function isExpired(?DateTime $now = null) : bool {
        if ( is_null($now) ) {
            $now = new DateTime;
        }
        return $this->expiration_date < $now;
    }

    /**
     * Get the current session id
     * @return SessionId
     */
    public function getId() : SessionId
    {
        return $this->id;
    }

    /**
     * Get the creation date of the session
     * @return DateTime
     */
    public function getCreationDate() : DateTime
    {
        return $this->creation_date;
    }

    /**
     * Get the expiration date
     * @return DateTime
     */
    public function getExpirationDate() : DateTime
    {
        return $this->expiration_date;
    }

}