<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class TableRepository extends DocumentRepository
{
    public function getAvailableTables(\DateTime $now){
        $reservationsRepository = $this->dm->getRepository('RestaurantTablesBundle:Reservation');
        $all_tables = $this->findBy(array('available' => true));
        $tables = array();
        foreach ($all_tables as $table) {
            if (count($reservationsRepository->getReservationsForTableNow($table, $now)) == 0) {
                $tables[] = $table;
            }
        }
        return $tables;
    }
}
