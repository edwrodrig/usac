<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\request;

use edwrodrig\query\Insert;
use edwrodrig\usac\request\ChangeMailRequest;

class InsertChangeMailRequest extends Insert
{
    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
INSERT INTO usac_change_mail_requests
(
    id_request,
    mail,
    creation_date,
    expiration_date,
    id_user
)
VALUES
(
    :id_request,
    :mail,
    :creation_date,
    :expiration_date,
    :id_user
)
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function set(ChangeMailRequest $request) {
        $this
            ->b('id_request', $request->get_id_request())
            ->b('mail', strval($request->get_mail()))
            ->b_datetime('creation_date', $request->get_creation_date())
            ->b_datetime('expiration_date', $request->get_expiration_date())
            ->b('id_user', $request->get_user()->get_id_user());
        return $this;
    }

}