<?php

namespace Restaurant\TablesBundle\Document;

class Order {

    private $id;
    private $items = array();
    private $date;
    private $table_id;
    private $employee;

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
    public function getTableId()
    {
        return $this->table_id;
    }

    /**
     * @param mixed $table_id
     */
    public function setTableId($table_id)
    {
        $this->table_id = $table_id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param mixed $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(Document\OrderItems $items)
    {
        $this->items[] = $items;
    }

}