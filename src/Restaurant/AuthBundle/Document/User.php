<?php

namespace Restaurant\AuthBundle\Document;

use Symfony\Component\Security\Core\User\UserInterface;
use Restaurant\CashBundle\Document\Employee;


class User implements UserInterface, \Serializable
{

    private $id;
    private $username;
    private $password;
    private $email;
    private $salt;
    private $isActive;
    private $roles;
    private $employee;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = array('ROLE_USER');
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * Get id
     *
     * @return \MongoId $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Add role
     * @param string $role
     * @return self
     */
    public function addRole($role)
    {
        if (!in_array($role, $this->roles))
            $this->roles[] = $role;
        return $this;
    }

    /**
     * Remove role
     * @param string $role
     * @return self
     */
    public function removeRole($role)
    {
        $this->roles = array_diff($this->roles, array($role));
        return $this;
    }

    /**
     * Set employee
     *
     * @param Employee $employee
     * @return self
     */
    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * Get employee
     *
     * @return Employee $employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }
}
