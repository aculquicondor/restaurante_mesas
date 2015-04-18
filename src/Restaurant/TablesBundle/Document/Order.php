<?php

namespace Restaurant\TablesBundle\Document;

class Order {

    private $id;
    private $orderItems = array();
    private $date;
    private $table;
    private $employee;



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
     * Set date
     *
     * @param date $date
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
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set employee
     *
     * @param Restaurant\TablesBundle\Document\Employee $employee
     * @return self
     */
    public function setEmployee(\Restaurant\TablesBundle\Document\Employee $employee)
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * Get employee
     *
     * @return Restaurant\TablesBundle\Document\Employee $employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set table
     *
     * @param Restaurant\TablesBundle\Document\Table $table
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
     * @return Restaurant\TablesBundle\Document\Table $table
     */
    public function getTable()
    {
        return $this->table;
    }
}
