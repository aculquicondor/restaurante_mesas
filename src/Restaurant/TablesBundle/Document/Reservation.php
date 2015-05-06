<?php

// src/Restaurant/TablesBundle/Document/Reservation.php
namespace Restaurant\TablesBundle\Document;


class Reservation
{
    protected $id;
    protected $tables = array();
    protected $client;
    protected $date;
    protected $estimatedTime;

    public function __construct()
    {
        $this->tables = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param int|\MongoDate $date
     * @return self
     */
    public function setDate($date)
    {
        if ($date instanceof \MongoDate)
            $this->date = $date;
        else
            $this->date = new \MongoDate($date);
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
     * Set estimatedTime
     *
     * @param int|\MongoTimestamp $estimatedTime
     * @return self
     */
    public function setEstimatedTime($estimatedTime)
    {
        if ($estimatedTime instanceof \MongoTimestamp)
            $this->estimatedTime = $estimatedTime;
        else
            $this->estimatedTime = new \MongoTimestamp($estimatedTime);
        return $this;
    }

    /**
     * Get estimatedTime
     *
     * @return \MongoTimestamp $estimatedTime
     */
    public function getEstimatedTime()
    {
        return $this->estimatedTime;
    }

    /**
     * Set client
     *
     * @param \Restaurant\CashBundle\Document\Client $client
     * @return self
     */
    public function setClient(\Restaurant\CashBundle\Document\Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return \Restaurant\CashBundle\Document\Client $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add table
     *
     * @param \Restaurant\TablesBundle\Document\Table $table
     */
    public function addTable(\Restaurant\TablesBundle\Document\Table $table)
    {
        $this->tables[] = $table;
    }

    /**
     * Remove table
     *
     * @param \Restaurant\TablesBundle\Document\Table $table
     */
    public function removeTable(\Restaurant\TablesBundle\Document\Table $table)
    {
        $this->tables->removeElement($table);
    }

    /**
     * Get tables
     *
     * @return \Doctrine\Common\Collections\Collection $tables
     */
    public function getTables()
    {
        return $this->tables;
    }
}
