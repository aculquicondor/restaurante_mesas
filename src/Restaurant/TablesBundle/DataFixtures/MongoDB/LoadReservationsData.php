<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\Reservation;

class LoadReservationsData extends AbstractFixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $reservation = new Reservation();
        $reservation->addTable($this->getReference('table-reserve-now'));
        $reservation->setEstimatedArrivalTime(new \DateTime('2015-05-06 09:10'));
        $manager->persist($reservation);

        $reservation = new Reservation();
        $reservation->addTable($this->getReference('table-reserve-later'));
        $reservation->setEstimatedArrivalTime(new \DateTime('2015-05-06 11:01'));
        $manager->persist($reservation);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array('Restaurant\TablesBundle\DataFixtures\MongoDB\LoadTablesData');
    }
}