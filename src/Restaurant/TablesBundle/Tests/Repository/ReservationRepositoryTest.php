<?php

namespace Restaurant\TablesBundle\Tests\Repository;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Repository\ReservationRepository;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadReservationsData;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\ODM\MongoDB\DocumentManager;


class ReservationRepositoryTest extends KernelTestCase
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * @var LoadReservationsData
     */
    private $reservationsFixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
        $this->reservationRepository = $this->dm
            ->getRepository('RestaurantTablesBundle:Reservation');

        $loader = new Loader();
        $this->reservationsFixture = new LoadReservationsData();
        $loader->addFixture($this->reservationsFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($this->dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testNoReservationForNow()
    {
        $now = new \DateTime('2015-05-06 09:05');
        $result = $this->reservationRepository->getReservationsForTableNow(
            $this->reservationsFixture->getReference('table-reserve-now'), $now);
        $this->assertEquals(1, count($result));
    }

    public function testReservationForNow()
    {
        $now = new \DateTime('2015-05-06 09:05');
        $result = $this->reservationRepository->getReservationsForTableNow(
            $this->reservationsFixture->getReference('table-reserve-later'), $now);
        $this->assertEquals(0, count($result));
    }

    public function testReservationForNowOn()
    {
        $now = new \DateTime('2015-05-06 09:05');
        $result = $this->reservationRepository->getReservationsForTableNowOn(
            $this->reservationsFixture->getReference('table-reserve-later'), $now);
        $this->assertEquals(1, count($result));
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->dm->close();
    }
}