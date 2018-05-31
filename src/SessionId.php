<?php
declare(strict_types=1);

namespace edwrodrig\usac;

/**
 * Class SessionId
 * @api
 * @package edwrodrig\usac
 */
class SessionId
{
    /**
     * The id as a
     * @var string
     */
    private $id;

    /**
     * SessionId constructor.
     *
     * By default generates a random sequence
     * @api
     * @return SessionId
     * @throws \Exception
     */
    public static function createNew() : SessionId {
        return new self(bin2hex(random_bytes(32)));
    }


    /**
     * SessionId constructor.
     *
     * Creates a session from a predefined id
     * @api
     * @param string $id
     */
    public function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * Get the session id as string
     * @api
     * @return null|string
     */
    public function __toString() {
        return $this->id;
    }
}