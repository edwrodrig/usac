<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:21
 */

namespace edwrodrig\usac\request;


class Request
{
    protected $id_request;
    protected $mail;

    protected $creation_date;
    protected $expiration_date;

    protected function init_id_request() {
        $this->id_request = bin2hex(random_bytes(32));
    }

    public function is_expired() : bool {
        return $this->expiration_date < new \DateTime;
    }

}