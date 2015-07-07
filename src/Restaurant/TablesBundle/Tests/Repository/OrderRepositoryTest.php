<?php
/**
 * Created by PhpStorm.
 * User: alculquicondor
 * Date: 6/23/15
 * Time: 9:50 PM
 */

namespace Restaurant\TablesBundle\Tests\Repository;

use Restaurant\TablesBundle\Tests\WebTestCase;
use Restaurant\TablesBundle\Repository\OrderRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadOrdersData;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Loader;


class OrderRepositoryTest extends WebTestCase {

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var AbstractFixture
     */
    private $fixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
        $this->orderRepository = $this->dm
            ->getRepository('RestaurantTablesBundle:Order');

        $loader = new Loader();
        $this->fixture = new LoadOrdersData();
        $loader->addFixture($this->fixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($this->dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testFindAllForEmployee()
    {
        $employee = $this->fixture->getReference('scarlet-johanson');
        $results = $this->orderRepository->findAll($employee);
        $this->assertEquals(2, count($results));
    }

    public function testGetActive()
    {
        $results = $this->orderRepository->getActiveOrders();
        $this->assertEquals(2, count($results));
    }

    public function testGetActiveForEmployee()
    {
        $employee = $this->fixture->getReference('scarlet-johanson');
        $results = $this->orderRepository->getActiveOrders($employee);
        $this->assertEquals(1, count($results));
    }

    public function testGetDayOrders()
    {
        $results = $this->orderRepository->getDayOrders(new \DateTime('2015-05-06 19:00'));
        $this->assertEquals(2, count($results));
    }

    public function testGetDayOrdersForEmployee()
    {
        $employee = $this->fixture->getReference('scarlet-johanson');
        $results = $this->orderRepository->getDayOrders(
            new \DateTime('2015-05-06 19:00'), $employee);
        $this->assertEquals(1, count($results));
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->dm->close();
    }

}