<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:59
 */

namespace edwrodrig\usac\query\request;

use edwrodrig\query\Select;
use edwrodrig\usac\Email;
use edwrodrig\usac\query\user\exception\RegistrationRequestDoesNotExistException;
use edwrodrig\usac\request\RegistrationRequest;

class SelectRegistrationRequest extends Select
{
    private $id_request;

    public function __construct(\PDO $pdo) {
        $stmt = <<<SQL
SELECT
    id_request,
    mail,
    creation_date,
    expiration_date
FROM
    usac_registration_requests
WHERE
    id_request = :id_request
SQL;


        parent::__construct($pdo, $stmt);
    }

    public function where(int $id_request) {
        $this->id_request = $id_request;
        $this
            ->b('id_request', $id_request);

        return $this;
    }

    /**
     * @return RegistrationRequest
     * @throws RegistrationRequestDoesNotExistException
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\usac\exception\InvalidMailException
     */
    public function get() : RegistrationRequest {
        if ( $row = $this->select()->fetch() ) {
            return $this->create_from_row($row);
        } else {
            throw new RegistrationRequestDoesNotExistException($this->id_request);
        }
    }

    /**
     * @param array $row
     * @return RegistrationRequest
     * @throws \edwrodrig\usac\exception\InvalidMailException
     */
    public function create_from_row(array $row) {
        $row['mail'] = new Email($row['mail']);
        $row['creation_date'] = $this->create_datetime($row['creation_date']);
        $row['expiration_date'] = $this->create_datetime($row['expiration_date']);

        return RegistrationRequest::create_from_array($row);
    }

}