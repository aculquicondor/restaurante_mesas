<?php

// src/Restaurant/CashBundle/Document/Client.php
namespace Restaurant\CashBundle\Document;


class Client
{
    protected $id;
    protected $dni;
    protected $ruc;
    protected $name;
    protected $address;

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
     * Set dni
     *
     * @param string $dni
     * @return self
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * Get dni
     *
     * @return string $dni
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set ruc
     *
     * @param string $ruc
     * @return self
     */
    public function setRuc($ruc)
    {
        $this->ruc = $ruc;
        return $this;
    }

    /**
     * Get ruc
     *
     * @return string $ruc
     */
    public function getRuc()
    {
        return $this->ruc;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
