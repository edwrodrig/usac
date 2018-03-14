<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\request;

use edwrodrig\query\Insert;
use edwrodrig\usac\request\RegistrationRequest;
use edwrodrig\usac\Session;
use edwrodrig\usac\User;

class InsertRegistrationRequest extends Insert
{
    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
INSERT INTO usac_registration_requests
(
    id_request,
    mail,
    creation_date,
    expiration_date
)
VALUES
(
    :id_request,
    :mail,
    :creation_date,
    :expiration_date
)
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function set(RegistrationRequest $request) {
        $this
            ->b('id_request', $request->get_id_request())
            ->b('mail', strval($request->get_mail()))
            ->b_datetime('creation_date', $request->get_creation_date())
            ->b_datetime('expiration_date', $request->get_expiration_date());
        return $this;
    }

}