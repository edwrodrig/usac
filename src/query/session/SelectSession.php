<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 16:07
 */

namespace edwrodrig\usac\query\session;


use edwrodrig\query\Select;
use edwrodrig\usac\Email;
use edwrodrig\usac\User;

abstract class SelectSession extends Select
{

    private $id_session;

    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
SELECT
  id_session,
  id_user,
  creation_date,
  expiration_date
FROM
  usac_sessions
WHERE
  id_session = :id_session
SQL;
        parent::__construct($pdo, $stmt);
    }

    public function where(string $id_session) {
        $this->id_session = $id_session;

        $this
            ->b('id_session', $id_session);

        return $this;
    }

    public function get() : User {
        if ( $row = $this->select()->fetch() ) {
            return $this->create_from_row($row);
        } else {
            throw new SessionDoesNotExists($this->id_session);
        }
    }

    /**
     * @param array $row
     * @return User
     */
    public function create_from_row(array $row) : User {
        $row['mail'] = new Email($row['mail']);
        $row['creation_date'] = $this->create_datetime($row['creation_date']);
        $row['expiration_date'] = $this->create_datetime($row['expiration_date']);

        return User::create_from_array($row);
    }

}