<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Document\MenuItem;
use Doctrine\ODM\MongoDB\DocumentManager;

class MenuItemTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private static $dm;

    /**
     * @var MenuItem
     */
    private $menuItem;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
    }

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->menuItem = new MenuItem();
        $this->menuItem->setName("Falafel");
        $this->menuItem->setAvailable(true);
        $this->menuItem->setPrice(2.0);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();
        $this->assertNotNull($this->menuItem->getId());
    }

    public function testUpdate()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $menuItems = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\MenuItem')
            ->findAndUpdate()
            ->field("id")->equals($this->menuItem->getId())
            ->field("price")->set(3.00)
            ->getQuery()->execute();

        foreach ($menuItems as $m) {
            $this->assertNotEquals($this->menuItem->getPrice(), $m->getPrice());
        }

    }

    public function testRemove()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();
        self::$dm->remove($this->menuItem);
        self::$dm->flush();
        $docMenuItem = self::$dm->getRepository('RestaurantTablesBundle:MenuItem')->findById($this->menuItem->getId());
        $this->assertEmpty($docMenuItem);
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$dm->close();
    }
}