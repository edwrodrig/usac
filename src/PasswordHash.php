<?php
declare(strict_types=1);

namespace edwrodrig\usac;

/**
 * Class PasswordHash
 *
 * Just a wrapper class for password functionalities
 * The usage for this class is something like this:
 *
 * To store the password
 * ```
 * $hash = PasswordHash::createFromPassword('user_defined_password');
 * store_in_db(strval($hash));
 * ```
 *
 * To use the password
 * ```
 * $hash = new PasswordHash($stored_hash);
 * if ( $hash->check('user_login_input_password') )
 *   echo 'LOGIN!!!';
 * else
 *   echo 'WRONG PASSWORD!! GO AWAY!';
 * ```
 *
 *
 * @api
 * @package edwrodrig\usac
 */
class PasswordHash
{

    /**
     * A hash build using {@see password_hash()}
     * @var string
     */
    private $hash;

    /**
     * Creates a new password hash from function
     * @api
     * @param string $new_password
     * @return PasswordHash
     */
    public static function createFromPassword(string $new_password) : PasswordHash {
        $p = new self(password_hash($new_password, PASSWORD_DEFAULT));
        return $p;

    }

    /**
     * PasswordHash constructor.
     * @api
     * @param string $hash
     */
    public function __construct(string $hash) {
        $this->hash = $hash;
    }

    /**
     * Check if this hash match a password.
     *
     * Use this function to check if a password input match with the stored password as a hash.
     * This class must contain the stored password hash
     * @api
     * @uses password_verify()
     * @param string $password
     * @return bool
     */
    public function check(string $password) : bool {
        return password_verify($password, $this->hash);
    }

    /**
     * Get the hash as a string.
     *
     * The string is suitable for use in {PasswordHash::__construct()}
     * @api
     * @return string
     */
    public function __toString() : string {
        return $this->hash;
    }
}