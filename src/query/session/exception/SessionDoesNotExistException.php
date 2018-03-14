<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 19:01
 */

namespace edwrodrig\usac\query\session\exception;


class SessionDoesNotExistException extends \Exception
{

    /**
     * SessionDoesNotExists constructor.
     * @param $id_session
     */
    public function __construct(string $id_session)
    {
        parent::__construct($id_session);
    }
}