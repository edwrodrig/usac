<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 22:35
 */

namespace edwrodrig\usac;


use edwrodrig\usac\exception\EmailAlreadyRegisteredException;
use edwrodrig\usac\exception\RequestExpiredException;
use edwrodrig\usac\query\request\InsertChangeMailRequest;
use edwrodrig\usac\query\request\InsertRegistrationRequest;
use edwrodrig\usac\query\request\SelectChangeMailRequest;
use edwrodrig\usac\query\request\SelectRegistrationRequest;
use edwrodrig\usac\query\session\DeleteSession;
use edwrodrig\usac\query\session\InsertSession;
use edwrodrig\usac\query\session\SelectSession;
use edwrodrig\usac\query\user\InsertUser;
use edwrodrig\usac\query\user\SelectUserByMail;
use edwrodrig\usac\query\user\UpdateUserMail;
use edwrodrig\usac\request\ChangeMailRequest;
use edwrodrig\usac\request\RegistrationRequest;

class Usac
{
    /**
     * @var \PDO
     */
    private $pdo;

    private $registration_request_duration;

    private $change_mail_request_duration;

    private $session_duration;


    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
        $this->registration_request_duration = new \DateInterval('PT24H');
    }

    /**
     * @param Email $mail
     * @return bool
     * @throws \edwrodrig\query\exception\SelectException
     */
    public function is_email_registered(Email $mail) : bool {
        return
            SelectUserByMail::init($this->pdo)
                ->where($mail)
                ->select()->fetch() !== FALSE;
    }

    /**
     * @param Email $mail
     * @return RegistrationRequest
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \Exception
     */
    public function request_registration(Email $mail) {

        if ( $this->is_email_registered($mail) ) {
            throw new EmailAlreadyRegisteredException($mail);
        }

        $request = RegistrationRequest::create_from_request($mail, $this->registration_request_duration);

        InsertRegistrationRequest::init($this->pdo)
            ->set($request)
            ->insert();

        //send mail?

        return $request;
    }

    /**
     * @param string $id_request
     * @return RegistrationRequest
     * @throws RequestExpiredException
     * @throws \edwrodrig\query\exception\SelectException
     * @throws exception\InvalidMailException
     * @throws query\user\exception\RegistrationRequestDoesNotExistException
     */
    public function get_registration_request(string $id_request) : RegistrationRequest {
        $request = SelectRegistrationRequest::init($this->pdo)
            ->where($id_request)
            ->get();

        if ( $request->is_expired() )
            throw new RequestExpiredException($id_request);

        return $request;
    }

    /**
     * @param string $id_request
     * @param string $name
     * @param string $password
     * @throws RequestExpiredException
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\query\exception\UpdateException
     * @throws exception\InvalidMailException
     * @throws query\user\exception\RegistrationRequestDoesNotExistException
     */
    public function register_user(string $id_request, string $name, string $password) {
        $request = $this->get_registration_request($id_request);

        $user = User::create_new_user($name, $password, $request->get_mail());

        InsertUser::init($this->pdo)
            ->set($user)
            ->insert();
    }


    /**
     * @param User $user
     * @param Email $mail
     * @return ChangeMailRequest
     * @throws \Exception
     * @throws \edwrodrig\query\exception\SelectException
     */
    public function request_change_mail(User $user, Email $mail) {
        if ( $this->is_email_registered($mail) ) {
            throw new EmailAlreadyRegisteredException($mail);
        }

        $request = ChangeMailRequest::create_from_request($user, $mail, $this->change_mail_request_duration);

        InsertChangeMailRequest::init($this->pdo)
            ->set($request)
            ->insert();

        //send mail?

        return $request;
    }

    /**
     * @param string $id_request
     * @return ChangeMailRequest
     * @throws RequestExpiredException
     * @throws \edwrodrig\query\exception\SelectException
     * @throws \edwrodrig\query\exception\UpdateException
     * @throws exception\InvalidMailException
     * @throws query\user\exception\ChangeMailRequestDoesNotExistException
     */
    public function get_change_mail_request(string $id_request) {
        $request = SelectChangeMailRequest::init($this->pdo)
            ->where($id_request)
            ->get();

        if ( $request->is_expired() ) {
            throw new RequestExpiredException($id_request);
        }

        $user = $request->get_user();
        $user->set_mail($request->get_mail());

        UpdateUserMail::init($this->dao)
            ->set($user)
            ->update();

        return $request;
    }

    /**
     * @param User $user
     * @return Session
     * @throws \Exception
     */
    public function session_create(User $user) : Session {
        $session = Session::create_new_session($user, $this->session_duration);

        InsertSession::init($this->pdo)
            ->set($session)
            ->insert();

        return $session;
    }

    /**
     * @param Session $session
     * @throws \edwrodrig\query\exception\UpdateException
     */
    public function session_close(Session $session) {
        DeleteSession::init($this->pdo)
            ->where($session)
            ->delete();
    }

    /**
     * @param string $id_session
     * @return Session
     * @throws \edwrodrig\query\exception\SelectException
     * @throws exception\InvalidMailException
     * @throws query\session\exception\SessionDoesNotExistException
     */
    public function session_get(string $id_session) : Session {
        $session = SelectSession::init($this->pdo)
            ->where($id_session)
            ->get();

        return $session;
    }





}