<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 23:27
 */

namespace edwrodrig\usac\exception;


class RequestExpiredException extends \Exception
{

    /**
     * RequestExpiredException constructor.
     * @param string $id_request
     */
    public function __construct(string $id_request)
    {
        parent::__construct("[$id_request]");
    }
}