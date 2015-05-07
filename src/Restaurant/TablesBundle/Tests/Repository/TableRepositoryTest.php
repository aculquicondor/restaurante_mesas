<?php

namespace Restaurant\TablesBundle\Tests\Repository;

use Restaurant\TablesBundle\Tests\KernelTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadReservationsData;
use Restaurant\TablesBundle\Repository\TableRepository;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;


class TableRepositoryTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var TableRepository
     */
    private $tableRepository;

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
        $this->tableRepository = $this->dm
            ->getRepository('RestaurantTablesBundle:Table');

        $loader = new Loader();
        $this->reservationsFixture = new LoadReservationsData();
        $loader->addFixture($this->reservationsFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($this->dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testBusyDay()
    {
        $now = new \DateTime('2015-05-06 09:05');
        $result = $this->tableRepository->getAvailableTables($now);
        $this->assertEquals(2, count($result));
    }

    public function testPrevDay()
    {
        $now = new \DateTime('2015-05-05 09:05');
        $result = $this->tableRepository->getAvailableTables($now);
        $this->assertEquals(3, count($result));
    }

    public function testNextDay()
    {
        $now = new \DateTime('2015-05-07 09:05');
        $result = $this->tableRepository->getAvailableTables($now);
        $this->assertEquals(3, count($result));
    }
}
