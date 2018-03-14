<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\request;

use edwrodrig\query\Delete;
use edwrodrig\usac\request\RegistrationRequest;

class DeleteRegistrationRequest extends Delete
{
    public function __construct(\PDO $pdo) {
        parent::__construct($pdo, "DELETE FROM usac_registration_requests WHERE id_request = :id_request");
    }

    /**
     * @param RegistrationRequest $request
     * @return $this
     */
    public function where(RegistrationRequest $request) {
        $this
            ->b('id_request', $request->get_id_request());
        return $this;
    }

}