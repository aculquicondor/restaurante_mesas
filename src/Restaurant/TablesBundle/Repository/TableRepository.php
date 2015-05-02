<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class TableRepository extends DocumentRepository
{
    public function getAvailableTables(){
        $tables = $this->findBy(array('available' => 1));
        $usable_reservations = $this->get('doctrine_mongodb')
            ->getRepository('RestaurantTablesBundle:Reservation')
            ->getAvailableReservationsForUse();
        $available_tables = array();
        foreach($usable_reservations as $reservation){
            $available_tables[] = $reservation->getTables();
        }
        $available_tables = $available_tables + $tables;
        return $available_tables;
    }
}
