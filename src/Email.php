<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:29
 */

namespace edwrodrig\usac;


use edwrodrig\usac\exception\InvalidMailException;

class Email
{
    private $mail;

    /**
     * Email constructor.
     * @param string $mail
     * @throws InvalidMailException
     */
    public function __construct(string $mail) {
        $this->mail = filter_var($mail, FILTER_VALIDATE_EMAIL);
        if ( $this->mail === FALSE ) {
            throw new InvalidMailException($mail);
        }
    }

    public function __toString() : string {
        return $this->mail;
    }

}