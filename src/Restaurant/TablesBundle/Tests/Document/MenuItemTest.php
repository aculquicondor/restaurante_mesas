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

    public function testUpdatePrice()
    {
        $oldPrice = $this->menuItem->getPrice();
        $newPrice = 3.5;
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->menuItem->setPrice($newPrice);
        $docMenuItem = self::$dm->getRepository("RestaurantTablesBundle:MenuItem")->find($this->menuItem->getId());
        $this->assertNotEquals($oldPrice, $docMenuItem->getPrice());
    }

    public function testUpdateName()
    {
        $oldName = $this->menuItem->getName();
        $newName = "Falafel verde";
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->menuItem->setName($newName);
        $docMenuItem = self::$dm->getRepository("RestaurantTablesBundle:MenuItem")->find($this->menuItem->getId());
        $this->assertNotEquals($oldName, $docMenuItem->getName());
    }

    public function testUpdateAvailable()
    {
        $oldAvailable = $this->menuItem->getAvailable();
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->menuItem->setAvailable(false);
        $docMenuItem = self::$dm->getRepository("RestaurantTablesBundle:MenuItem")->find($this->menuItem->getId());
        $this->assertNotEquals($oldAvailable, $docMenuItem->getAvailable());
    }

    public function testRemove()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();
        self::$dm->remove($this->menuItem);
        self::$dm->flush();
        $docMenuItem = self::$dm->getRepository("RestaurantTablesBundle:MenuItem")->find($this->menuItem->getId());
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