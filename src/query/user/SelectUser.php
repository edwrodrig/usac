<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 16:07
 */

namespace edwrodrig\usac\query\user;


use edwrodrig\query\Select;
use edwrodrig\usac\Email;
use edwrodrig\usac\User;

abstract class SelectUser extends Select
{

    public function get() : User {
        if ( $row = $this->select()->fetch() ) {
            return $this->create_from_row($row);
        } else {
            $this->throw_exception();
        }
    }

    abstract protected function throw_exception();

    /**
     * @param array $row
     * @return User
     */
    public function create_from_row(array $row) : User {
        $row['mail'] = new Email($row['mail']);

        return User::create_from_array($row);
    }

}