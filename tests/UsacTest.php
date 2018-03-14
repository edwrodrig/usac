<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 23:30
 */

use edwrodrig\usac\Email;
use edwrodrig\usac\query\request\exception\ChangeMailRequestDoesNotExistException;
use edwrodrig\usac\query\session\exception\SessionDoesNotExistException;
use edwrodrig\usac\query\request\exception\RegistrationRequestDoesNotExistException;
use edwrodrig\usac\query\user\exception\UserDoesNotExistException;
use edwrodrig\usac\query\user\SelectUserByMail;
use edwrodrig\usac\query\user\SelectUserByName;
use edwrodrig\usac\tests\TableTestCase;
use edwrodrig\usac\Usac;
use edwrodrig\usac\User;

class UsacTest extends TableTestCase
{
    /**
     * @throws Exception
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\query\exception\UpdateException
     * @throws \edwrodrig\usac\exception\InvalidMailException
     * @throws \edwrodrig\usac\exception\RequestExpiredException
     * @throws \edwrodrig\usac\query\user\exception\RegistrationRequestDoesNotExistException
     */
    function testRegisterUser() {

        $usac = new Usac($this->pdo);
        $request = $usac->request_registration(new Email('edwin@mail.com'));

        $retrieved_request = $usac->get_registration_request($request->get_id_request());

        $this->assertEquals($request->get_id_request(), $retrieved_request->get_id_request());

        $this->assertEquals($request->get_mail(), $retrieved_request->get_mail());

        $usac->register_user($retrieved_request->get_id_request(), 'edwin','pass');

        $user = SelectUserByName::init($this->pdo)
            ->where('edwin')
            ->get();

        $this->assertEquals('edwin', $user->get_name());
        $this->assertTrue($user->check_password('pass'));

        try {
            $usac->get_registration_request($request->get_id_request());
        } catch ( \Exception $e ) {
            $this->assertInstanceOf(RegistrationRequestDoesNotExistException::class, $e);
            $this->assertEquals($request->get_id_request(), $e->getMessage());
        }

    }

    /**
     * @throws Exception
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\query\exception\UpdateException
     * @throws \edwrodrig\usac\exception\InvalidMailException
     */
    function testSession() {
        $usac = new Usac($this->pdo);

        $user = User::create_new_user('edwin', 'pass', new Email('edwin@mail.com'));

        $usac->user_create($user);

        $user = SelectUserByName::init($this->pdo)
            ->where('edwin')
            ->get();

        $this->assertEquals('edwin', $user->get_name());
        $this->assertTrue($user->check_password('pass'));

        $session = $usac->session_create($user);

        $retrieved_session = $usac->session_get($session->get_id_session());

        $this->assertEquals($session->get_id_session(), $retrieved_session->get_id_session());
        $this->assertEquals($user->get_id_user(), $retrieved_session->get_user()->get_id_user());

        $usac->session_close($retrieved_session);

        try {
            $usac->session_get($retrieved_session->get_id_session());
        } catch ( \Exception $e) {
            $this->assertInstanceOf(SessionDoesNotExistException::class, $e);
            $this->assertEquals($retrieved_session->get_id_session(), $e->getMessage());
        }
    }

    /**
     * @throws Exception
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\query\exception\UpdateException
     * @throws \edwrodrig\usac\exception\InvalidMailException
     * @throws \edwrodrig\usac\exception\RequestExpiredException
     *
     */
    function testChangeMail() {
        $usac = new Usac($this->pdo);

        $user = User::create_new_user('edwin', 'pass', new Email('edwin@mail.com'));

        $usac->user_create($user);

        $user = SelectUserByName::init($this->pdo)
            ->where('edwin')
            ->get();

        $request = $usac->request_change_mail($user, new Email('other@mail.com'));

        $retrieved_request = $usac->get_change_mail_request($request->get_id_request());

        try {
            $usac->get_change_mail_request($request->get_id_request());
        } catch ( \Exception $e ) {
            $this->assertInstanceOf(ChangeMailRequestDoesNotExistException::class, $e);
            $this->assertEquals($retrieved_request->get_id_request(), $e->getMessage());
        }

        $user = SelectUserByName::init($this->pdo)
            ->where('edwin')
            ->get();

        $this->assertEquals('other@mail.com', strval($user->get_mail()));

        try {
            SelectUserByMail::init($this->pdo)
                ->where(new Email('edwin@mail.com'))
                ->get();
        } catch ( \Exception $e) {
            $this->assertInstanceOf(UserDoesNotExistException::class,$e);
            $this->assertEquals("mail[edwin@mail.com]", $e->getMessage());
        }
}


}
