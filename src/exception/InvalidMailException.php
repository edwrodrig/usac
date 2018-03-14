<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:58
 */

namespace edwrodrig\usac\exception;


class InvalidMailException extends \Exception
{

    /**
     * InvalidMailException constructor.
     * @param string $mail
     */
    public function __construct(string $mail)
    {
        parent::__construct("$mail");
    }
}