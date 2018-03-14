<?php

namespace edwrodrig\usac\tests;

use edwrodrig\query\Util;
use edwrodrig\usac\request\ChangeMailRequest;
use edwrodrig\usac\request\RegistrationRequest;
use edwrodrig\usac\Session;
use edwrodrig\usac\User;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 23:42
 */

class TableTestCase extends TestCase
{

    /**
     * @var PDO
     */
    protected $pdo;

    function setUp() {
        $this->pdo = Util::set_default_attributes(new PDO('sqlite::memory:'));
        $this->pdo->exec(User::TABLE);
        $this->pdo->exec(Session::TABLE);
        $this->pdo->exec(RegistrationRequest::TABLE);
        $this->pdo->exec(ChangeMailRequest::TABLE);
    }
}
