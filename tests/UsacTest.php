<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 23:30
 */

use edwrodrig\usac\Email;
use edwrodrig\usac\tests\TableTestCase;
use edwrodrig\usac\Usac;

class UsacTest extends TableTestCase
{


    function testRegisterUser() {

        $usac = new Usac($this->pdo);
        $request = $usac->request_registration(new Email('edwin@mail.com'));
        $this->assertEquals('edwin@mail.com', strval($request->get_mail()));
    }
}
