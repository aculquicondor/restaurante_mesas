<?php

namespace Restaurant\TablesBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Order {

    private $id;
    private $orderItems = array();
    private $date;
    private $table;
    private $employee;


    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
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
     * Set date
     *
     * @param \MongoDate $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \MongoDate $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set employee
     *
     * @param \Restaurant\CashBundle\Document\Employee $employee
     * @return self
     */
    public function setEmployee(\Restaurant\CashBundle\Document\Employee $employee)
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * Get employee
     *
     * @return \Restaurant\CashBundle\Document\Employee $employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set table
     *
     * @param \Restaurant\TablesBundle\Document\Table $table
     * @return self
     */
    public function setTable(\Restaurant\TablesBundle\Document\Table $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Get table
     *
     * @return \Restaurant\TablesBundle\Document\Table $table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Add orderItem
     *
     * @param \Restaurant\TablesBundle\Document\OrderItem $orderItem
     */
    public function addOrderItem(\Restaurant\TablesBundle\Document\OrderItem $orderItem)
    {
        $this->orderItems[] = $orderItem;
    }

    /**
     * Remove orderItem
     *
     * @param \Restaurant\TablesBundle\Document\OrderItem $orderItem
     */
    public function removeOrderItem(\Restaurant\TablesBundle\Document\OrderItem $orderItem)
    {
        $this->orderItems->removeElement($orderItem);
    }

    /**
     * Get orderItems
     *
     * @return Collection $orderItems
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }
}
