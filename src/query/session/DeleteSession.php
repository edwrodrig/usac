<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\session;

use edwrodrig\query\Delete;
use edwrodrig\usac\Session;

class DeleteSession extends Delete
{
    public function __construct(\PDO $pdo) {
        parent::__construct($pdo, "DELETE FROM usac_sessions WHERE id_session = :id_session");
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function where(Session $session) {
        $this
            ->b('id_session', $session->get_id_session());
        return $this;
    }

}