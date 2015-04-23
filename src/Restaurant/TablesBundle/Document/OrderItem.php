<?php

// src/Restaurant/TablesBundle/Document/OrderItem.php
namespace Restaurant\TablesBundle\Document;

class OrderItem
{
    protected $id;
    protected $menu_item;
    protected $observations;


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
     * @param TablesBundle\Document\MenuItem $menuItem
     * @return self
     */
    public function setMenuItem(\TablesBundle\Document\MenuItem $menuItem)
    {
        $this->menu_item = $menuItem;
        return $this;
    }

    /**
     * Get menuItem
     *
     * @return TablesBundle\Document\MenuItem $menuItem
     */
    public function getMenuItem()
    {
        return $this->menu_item;
    }
}
