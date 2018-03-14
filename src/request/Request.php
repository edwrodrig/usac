<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 13-03-18
 * Time: 15:21
 */

namespace edwrodrig\usac\request;


use edwrodrig\usac\Email;

class Request
{
    /**
     * @return string
     */
    public function get_id_request(): string
    {
        return $this->id_request;
    }

    /**
     * @return Email
     */
    public function get_mail(): Email
    {
        return $this->mail;
    }

    /**
     * @return \DateTime
     */
    public function get_creation_date(): \DateTime
    {
        return $this->creation_date;
    }

    /**
     * @return \DateTime
     */
    public function get_expiration_date(): \DateTime
    {
        return $this->expiration_date;
    }
    /**
     * @var string
     */
    protected $id_request;
    /**
     * @var Email
     */
    protected $mail;

    /**
     * @var \DateTime
     */
    protected $creation_date;

    /**
     * @var \DateTime
     */
    protected $expiration_date;

    /**
     * @param \DateInterval $duration
     * @throws \Exception
     */
    protected function init(\DateInterval $duration) {
        $this->id_request = bin2hex(random_bytes(32));
        $this->creation_date = new \DateTime;
        $this->expiration_date = (clone $this->creation_date)->add($duration);
    }

    public function is_expired() : bool {
        return $this->expiration_date < new \DateTime;
    }

    public function from_array(array $data) {
        $this->id_request = $data['id_request'];
        $this->mail = $data['mail'];
        $this->creation_date = $data['creation_date'];
        $this->expiration_date = $data['expiration_date'];
    }

}