<?php

// src/Restaurant/TablesBundle/Document/Reservation.php
namespace Restaurant\TablesBundle\Document;


class Reservation
{
    protected $id;
    protected $tables = array();
    protected $client;
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
     * Set estimatedTime
     *
     * @param int|\MongoDate $estimatedTime
     * @return self
     */
    public function setEstimatedTime($estimatedTime)
    {
        if ($estimatedTime instanceof \MongoDate)
            $this->estimatedTime = $estimatedTime;
        else
            $this->estimatedTime = new \MongoDate($estimatedTime);
        return $this;
    }

    /**
     * Get estimatedTime
     *
     * @return \MongoDate $estimatedTime
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
