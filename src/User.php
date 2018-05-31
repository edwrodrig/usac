<?php
declare(strict_types=1);

namespace edwrodrig\usac;

/**
 * Class User
 *
 * Represent a user in the system.
 * This is useful to store user data while manipulating it.
 * Changes in this object do not implies changes in the database.
 * For example change the password here does not reflect this in a user login until this object is stored in the database
 * @package edwrodrig\usac
 */
class User
{
    /**
     * Id is the internal identificator for the user, it match the database id of the user.
     * Can be null if the
     * @var null|int
     */
    private $id = null;

    /**
     * This is the name of user. Commonly what is displayed and remembered by users.
     * It is what you put in a login form as a username.
     * @var
     */
    private $name;

    /**
     * @var PasswordHash
     */
    private $password_hash;

    /**
     * The email of the user.
     * A user account system is nothing if you does not have an email.
     * @var Email
     */
    private $mail = null;

    /**
     * The default preffered language of the user
     * @var string
     */
    private $default_lang = 'es';

    /**
     * Row id for serialization
     */
    const ID_ROW_NAME = 'id_user';
    const NAME_ROW_NAME = 'name';
    const PASSWORD_HASH_ROW_NAME = 'password_hash';
    const MAIL_ROW_NAME = 'mail';
    const DEFAULT_LANG_ROW_NAME = 'default_lang';

    /**
     * User constructor.
     * @internal
     */
    protected function __construct() {}

    /**
     * Create a new user from data.
     *
     * This create the user in the programming level.
     * Creating this object does creates in in the system, the database.
     * You need to sotre this object in some database.
     * @api
     * @param string $name
     * @param string $password
     * @param Email $mail
     * @return User
     */
    public static function createNewUser(string $name, string $password, Email $mail) {
        $user = new self;
        $user->name = $name;
        $user->password_hash = PasswordHash::createFromPassword($password);
        $user->mail = $mail;
        return $user;
    }

    /**
     * Create a user from an array.
     *
     * Useful to unserialize data from a database.
     * @api
     * @param $data
     * @return User
     */
    public static function createFromArray(array $data) : self
    {
        $user = new self;
        $user->id = $data[self::ID_ROW_NAME];
        $user->name = $data[self::NAME_ROW_NAME];
        $user->password_hash = new PasswordHash($data[self::PASSWORD_HASH_ROW_NAME]);
        $user->mail = $data[self::MAIL_ROW_NAME];
        $user->default_lang = $data[self::DEFAULT_LANG_ROW_NAME];
        return $user;
    }

    /**
     * Get the password hash.
     *
     * Retrieve this when you want check if a string {@see PasswordHash::check() match} with the user password
     * @api
     * @return PasswordHash
     */
    public function getPasswordHash() : PasswordHash {
        return $this->password_hash;
    }

    /**
     * Set the password hash.
     *
     * User this when you need to change the password hash.
     * Use this function changes the password of this object.
     * To change efectively in the system you need to save this object in the underlying database
     * @param PasswordHash $password
     * @return User
     */
    public function setPasswordHash(PasswordHash $password) : User {
        $this->password_hash = $password;
        return $this;
    }


    /**
     * Check if this object contains a new user
     *
     * A new user is some user that is not created in the database.
     * Internally tthis function just check if the id null, in other words, it does not have a id assigned.
     * Commonly the id is assigned when user data is inserted in some database and it return some identifier like a row id,
     * a current sequence number or an autoincrement primary key.
     * @api
     * @return bool
     */
    public function isNew() : bool {
        return is_null($this->id);
    }

    /**
     * Get the id
     *
     * A null id means that the user is not inserted in the database.
     * @api
     * @see User::isNew()
     * @uses User::id the attribute the get, see for more info
     * @return int|null
     */
    public function getId() : ?int {
        return $this->id;
    }

    /**
     * Get the user name.
     * @api
     * @uses User::$name
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }


    /**
     * Get the user mail.
     *
     * A user must have a email to send the password recovery, and confirmation emails
     * @api
     * @uses User::$mail
     * @return Email
     */
    public function getMail() : Email
    {
        return $this->mail;
    }

    /**
     * Get the default language of the user.
     *
     * This is useful when you need to display data to user in their preffered language
     * @api
     * @uses User::$default_lang
     * @return string
     */
    public function getDefaultLang() : string {
        return $this->default_lang;
    }

    /**
     * Set the mail of the user
     * @api
     * @param Email $mail
     * @return $this
     */
    public function setMail(Email $mail) : User
    {
        $this->mail = $mail;
        return $this;
    }

}