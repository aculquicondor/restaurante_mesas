<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class TableRepository extends DocumentRepository
{
    public function getAvailableTables(\DateTime $now){
        $reservationsRepository = $this->dm->getRepository('RestaurantTablesBundle:Reservation');
        $all_tables = $this->findBy(array('available' => 1));
        $tables = array();
        foreach($all_tables as $table) {
            if(empty($reservationsRepository->getReservationForTableNow($table, $now))) {
                $tables[] = $table;
            }
        }
        return $tables;
    }
}
