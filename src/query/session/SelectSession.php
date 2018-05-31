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
use edwrodrig\usac\query\session\exception\SessionDoesNotExistException;
use edwrodrig\usac\query\user\SelectUserById;
use edwrodrig\usac\Session;
use edwrodrig\usac\User;

class SelectSession extends Select
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

    /**
     * @return Session
     * @throws SessionDoesNotExistException
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\usac\exception\InvalidMailException
     */
    public function get() : Session {
        if ( $row = $this->select()->fetch() ) {
            return $this->create_from_row($row);
        } else {
            throw new SessionDoesNotExistException($this->id_session);
        }
    }

    /**
     * @param array $row
     * @return Session
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\usac\exception\InvalidMailException
     */
    public function create_from_row(array $row) : Session {
        $row['creation_date'] = $this->create_datetime($row['creation_date']);
        $row['expiration_date'] = $this->create_datetime($row['expiration_date']);

        $row['user'] = SelectUserById::init($this->pdo)
            ->where($row['id_user'])
            ->get();

        return Session::createFromArray($row);
    }

}