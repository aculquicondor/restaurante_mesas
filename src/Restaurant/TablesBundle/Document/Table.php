<?php

namespace Restaurant\TablesBundle\Document;


class Table
{
    protected $id;
    protected $number;
    protected $available;
    protected $occupationTime;
    protected $capacity;

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
     * Set number
     *
     * @param int $number
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = (int)$number;
        return $this;
    }

    /**
     * Get number
     *
     * @return int $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set available
     *
     * @param boolean $available
     * @return self
     */
    public function setAvailable($available)
    {
        $this->available = boolval($available);
        return $this;
    }

    /**
     * Get available
     *
     * @return boolean $available
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set occupationTime
     *
     * @param string|\DateTime $occupationTime
     * @return self
     */
    public function setOccupationTime($occupationTime)
    {
        if ($occupationTime instanceof \DateTime)
            $this->occupationTime = $occupationTime;
        else
            $this->occupationTime = new \DateTime($occupationTime);
        return $this;
    }

    /**
     * Get occupationTime
     *
     * @return \DateTime $occupationTime
     */
    public function getOccupationTime()
    {
        return $this->occupationTime;
    }

    /**
     * Set capacity
     *
     * @param int $capacity
     * @return self
     */
    public function setCapacity($capacity)
    {
        $this->capacity = (int)$capacity;
        return $this;
    }

    /**
     * Get capacity
     *
     * @return int $capacity
     */
    public function getCapacity()
    {
        return $this->capacity;
    }
}
