<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\session;

use edwrodrig\query\Insert;
use edwrodrig\usac\Session;
use edwrodrig\usac\User;

class InsertSession extends Insert
{
    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
INSERT INTO usac_sessions
(
    id_session,
    id_user,
    creation_date,
    expiration_date
)
VALUES
(
    :id_session,
    :id_user,
    :creation_date,
    :expiration_date
)
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function set(Session $session) {
        $this
            ->b('id_session', $session->get_id_session())
            ->b('id_user', $session->get_name()->get_id_user())
            ->b_datetime('creation_date', $session->get_creation_date())
            ->b_datetime('expiration_date', $session->get_expiration_date());
        return $this;
    }

}