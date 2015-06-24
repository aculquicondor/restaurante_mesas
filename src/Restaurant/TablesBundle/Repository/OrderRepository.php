<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Restaurant\CashBundle\Document\Employee;


class OrderRepository extends DocumentRepository
{
    public function findAll(Employee $waiter=null) {
        $queryBuilder = $this->createQueryBuilder();
        if ($waiter != null)
            $queryBuilder->field('employee')->references($waiter);
        return $queryBuilder->getQuery()->execute()->toArray(false);
    }

    public function getActiveOrders(Employee $waiter=null)
    {
        $queryBuilder = $this->createQueryBuilder()
            ->field('active')->equals(true);
        if ($waiter)
            $queryBuilder->field('employee')->references($waiter);
        return $queryBuilder->getQuery()->execute();
    }

    public function getDayOrders(\DateTime $day, Employee $waiter=null)
    {
        $day_start = new \DateTime($day->format('Y-m-d'));
        $day_end = clone $day_start;
        $day_end->add(new \DateInterval('P1D'));
        $queryBuilder = $this->createQueryBuilder()
            ->field('date')->range($day_start, $day_end);
        if ($waiter)
            $queryBuilder->field('employee')->references($waiter);
        return $queryBuilder->getQuery()->execute();
    }
}
