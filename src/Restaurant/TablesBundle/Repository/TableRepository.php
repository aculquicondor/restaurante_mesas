<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ReservationRepository extends DocumentRepository
{
    public function getReservationForTableNow(\Restaurant\TablesBundle\Document\Table $table, date $now)
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
