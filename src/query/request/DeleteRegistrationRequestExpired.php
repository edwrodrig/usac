<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\request;

use DateTime;
use edwrodrig\query\Delete;

class DeleteRegistrationRequestExpired extends Delete
{
    public function __construct(\PDO $pdo) {
        parent::__construct($pdo, "DELETE FROM usac_registration_requests WHERE expiration_date < :expiration_date");
    }

    /**
     * @param DateTime $expiration_date
     * @return $this
     */
    public function where(DateTime $expiration_date) {
        $this
            ->b_datetime('expiration_date', $expiration_date);
        return $this;
    }

}