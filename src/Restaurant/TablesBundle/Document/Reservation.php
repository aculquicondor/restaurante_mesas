<?php

// src/Restaurant/TablesBundle/Document/Reservation.php
namespace Restaurant\TablesBundle\Document;


class Reservation
{
    protected $id;
    protected $tables = array();
    protected $client;
    protected $estimatedArrivalTime;

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
     * @param string|\DateTime $estimatedArrivalTime
     * @return self
     */
    public function setEstimatedArrivalTime($estimatedArrivalTime)
    {
        if ($estimatedArrivalTime instanceof \DateTime)
            $this->estimatedArrivalTime = $estimatedArrivalTime;
        else
            $this->estimatedArrivalTime = new \DateTime($estimatedArrivalTime);
        return $this;
    }

    /**
     * Get estimatedTime
     *
     * @return \DateTime $estimatedTime
     */
    public function getEstimatedArrivalTime()
    {
        return $this->estimatedArrivalTime;
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
