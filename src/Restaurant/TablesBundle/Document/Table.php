<?php

namespace Restaurant\TablesBundle\Document;

use MongoId;
use MongoTimestamp;


class Table
{
    protected $id;
    protected $available;
    protected $occupation_time;
    protected $capacity;

    /**
     * Get id
     *
     * @return MongoId $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set available
     *
     * @param boolean $available
     * @return self
     */
    public function setAvailable($available)
    {
        $this->available = $available;
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
     * @param MongoTimestamp $occupationTime
     * @return self
     */
    public function setOccupationTime($occupationTime)
    {
        $this->occupation_time = $occupationTime;
        return $this;
    }

    /**
     * Get occupationTime
     *
     * @return MongoTimestamp $occupationTime
     */
    public function getOccupationTime()
    {
        return $this->occupation_time;
    }

    /**
     * Set capacity
     *
     * @param int $capacity
     * @return self
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
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
