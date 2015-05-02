<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Restaurant\TablesBundle\Document\Table;

class ReservationRepository extends DocumentRepository
{
    public function getReservationForTableNow(Table $table, \DateTime $now)
    {
        $reservations = $this->createQueryBuilder()
            ->field('date')->gte($now->sub(new \DateInterval('P1H')))
            ->lte($now->add(new \DateInterval('P1H')))
            ->field('tables')->includesReferenceTo($table)
            ->getQuery()
            ->execute();
        return $reservations;
    }
}
