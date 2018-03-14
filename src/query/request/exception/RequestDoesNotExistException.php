<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 22:22
 */

namespace edwrodrig\usac\query\user\exception;


class RequestDoesNotExistException extends \Exception
{
    public function __construct(string $id_request) {
        parent::__construct("[$id_request]");
    }
}