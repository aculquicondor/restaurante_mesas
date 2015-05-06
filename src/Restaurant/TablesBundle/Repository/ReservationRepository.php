<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Restaurant\TablesBundle\Document\Table;

class ReservationRepository extends DocumentRepository
{
    public function getReservationForTableNow(Table $table, \DateTime $now)
    {
        $start = clone $now;
        $start->sub(new \DateInterval('PT1H'));
        $end = clone $now;
        $end->add(new \DateInterval('PT1H'));

        $reservations = $this->createQueryBuilder()
            ->field('estimatedTime')
                ->range($start, $end)
            ->field('tables')->includesReferenceTo($table)
            ->getQuery()
            ->execute();
        return $reservations;
    }
}
