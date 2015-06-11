<?php

// src/Restaurant/TablesBundle/Document/OrderItem.php
namespace Restaurant\TablesBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\EmbeddedDocument;

/**
 * Class OrderItem
 * @package Restaurant\TablesBundle\Document
 * @EmbeddedDocument()
 */

class OrderItem
{
    protected $id;
    protected $menuItem;
    protected $observations;


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
     * Set observations
     *
     * @param string $observations
     * @return self
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
        return $this;
    }

    /**
     * Get observations
     *
     * @return string $observations
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set menuItem
     *
     * @param \Restaurant\TablesBundle\Document\MenuItem $menuItem
     * @return self
     */
    public function setMenuItem(\Restaurant\TablesBundle\Document\MenuItem $menuItem)
    {
        $this->menuItem = $menuItem;
        return $this;
    }

    /**
     * Get menuItem
     *
     * @return \Restaurant\TablesBundle\Document\MenuItem $menuItem
     */
    public function getMenuItem()
    {
        return $this->menuItem;
    }
}
