<?php

// src/Restaurant/TablesBundle/Document/OrderItem.php
namespace Restaurant\TablesBundle\Document;

class OrderItem
{
    protected $id;
    protected $menu_item;
    protected $observations;

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
}
