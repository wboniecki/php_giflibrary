<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 12.10.17
 * Time: 18:15
 */

namespace Entities;


class UsersEntity
{
    private $id;
    private $name;
    private $email;
    private $pass;
    private $privilege = 0;

    public function __construct($data)
    {
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->pass = $data['pass'];
        if(isset($data['privilege'])) {
            $this->privilege = $data['privilege'];
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return int
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }



}