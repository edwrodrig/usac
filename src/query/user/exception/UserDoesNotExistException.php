<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 16:18
 */

namespace edwrodrig\usac\query\user\exception;


class UserDoesNotExistException extends \Exception
{

    /**
     * UserDoesNotExistException constructor.
     * @param string $type
     * @param string $value
     */
    public function __construct(string $type, string $value)
    {
        parent::__construct(sprintf("%s[%s]", $type, $value));
    }
}