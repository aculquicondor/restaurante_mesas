<?php

namespace Restaurant\TablesBundle\Tests\Repository;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadMenuItemsData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Repository\MenuItemRepository;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\ODM\MongoDB\DocumentManager;


class MenuItemRepositoryTest extends KernelTestCase
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    /**
     * @var LoadMenuItemsData
     */
    private $menuItemFixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
        $this->menuItemRepository = $this->dm
            ->getRepository('RestaurantTablesBundle:MenuItem');

        $loader = new Loader();
        $this->menuItemFixture = new LoadMenuItemsData();
        $loader->addFixture($this->menuItemFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($this->dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testAvailable()
    {
        $result = $this->menuItemRepository->getAvailable();
        $this->assertEquals(1, count($result));
    }


    public function tearDown()
    {
        parent::tearDown();
        $this->dm->close();
    }
}