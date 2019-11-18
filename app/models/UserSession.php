<?php

namespace App\Models;

class UserSession extends \Phalcon\Mvc\Model
{
    protected $id;
    protected $token;
    protected $user_id;
    protected $expire_at;
    protected $created_at;
    protected $updated_at;

    public function initialize()
    {
        $this->setSource('user_session');
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getExpireAt()
    {
        return $this->expire_at;
    }

    public function setExpireAt($expire_at)
    {
        $this->expire_at = $expire_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdateAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
