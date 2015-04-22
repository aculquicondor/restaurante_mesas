<?php

namespace Restaurant\TablesBundle\Document;


class Store {

    /**
     * @var MongoId $id
     */
    protected $id;

    /**
     * @var string $address
     */
    protected $address;

    /**
     * @var \Restaurant\CashBundle\Document\Employee
     */
    protected $manager;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Set manager
     *
     * @param \Restaurant\CashBundle\Document\Employee $manager
     * @return self
     */
    public function setManager(\Restaurant\CashBundle\Document\Employee $manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * Get manager
     *
     * @return \Restaurant\CashBundle\Document\Employee $manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
