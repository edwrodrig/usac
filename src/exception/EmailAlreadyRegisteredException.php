<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 23:09
 */

namespace edwrodrig\usac\exception;


use edwrodrig\usac\Email;

class EmailAlreadyRegisteredException extends \Exception
{

    /**
     * EmailAlreadyRegisteredException constructor.
     * @param Email $mail
     */
    public function __construct(Email $mail)
    {
        parent::__construct(strval($mail));
    }
}