<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 14:46
 */

namespace edwrodrig\usac;


class User
{
    const TABLE = <<<SQL
CREATE TABLE usac_users (
  id_user INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  mail TEXT UNIQUE,
  default_lang TEXT NOT NULL
)
SQL;


    private $id_user = null;

    private $name;

    private $password_hash = null;

    private $mail = null;

    private $default_lang = 'es';

    protected function __construct() {}

    /**
     * @param $row
     * @return User
     */
    public static function create_from_array($row) : self
    {
        $user = new self;
        $user->id_user = $row['id_user'];
        $user->name = $row['name'];
        $user->password_hash = $row['password_hash'];
        $user->mail = $row['mail'];
        $user->default_lang = $row['default_lang'];
        return $user;
    }

    public function change_password(string $new_password) {
        $this->password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    }

    public function check_password(string $password) {
        return password_verify($password, $this->password_hash);
    }

    public function get_id_user() : int {
        return $this->id_user;
    }

    public function get_name() : string {
        return $this->name;
    }

    public function get_password_hash() : string {
        return $this->password_hash;
    }

    public function get_mail() : Email
    {
        return $this->mail;
    }

}