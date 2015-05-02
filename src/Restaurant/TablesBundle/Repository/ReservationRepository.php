<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ReservationRepository extends DocumentRepository
{
    public function getAvailableReservationsForUse()
    {
        return $this->createQueryBuilder()
            ->field('date')->gt((new \DateTime())->add(new \DateInterval('P1H')))
            ->getQuery()
            ->execute();
    }
}
