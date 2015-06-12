<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Restaurant\TablesBundle\Tests\KernelTestCase;
use Restaurant\TablesBundle\Document\OrderItem;
use Restaurant\TablesBundle\Document\MenuItem;
use Doctrine\ODM\MongoDB\DocumentManager;

class OrderItemTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private static $dm;

    /**
     * @var OrderItem
     */
    private $orderItem;

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
        $this->orderItem = new OrderItem();
        $this->orderItem->setDelivered(false);
        $this->orderItem->setObservations("Sin sal");

        $this->menuItem = new MenuItem();
        $this->menuItem->setName("Rocoto");
        $this->menuItem->setPrice(0.50);
        $this->menuItem->setAvailable(true);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->orderItem->setMenuItem($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();
        $this->assertNotNull($this->orderItem->getId());
    }

    public function testUpdateDelivered()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();

        $this->orderItem->setDelivered(true);
        $docOrderItem = self::$dm->getRepository("RestaurantTablesBundle:OrderItem")
            ->find($this->orderItem->getId());
        $this->assertTrue($docOrderItem->getDelivered());
    }

    public function testUpdateObservations()
    {
        $oldObservations = $this->orderItem->getObservations();
        $newObservations = "Sin sal y picante";
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->orderItem->setMenuItem($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();

        $this->orderItem->setObservations($newObservations);
        $docOrderItem = self::$dm->getRepository("RestaurantTablesBundle:OrderItem")->find($this->orderItem->getId());
        $this->assertNotEquals($oldObservations, $docOrderItem->getObservations());
    }

    public function testMenuItem()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->orderItem->setMenuItem($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();

        $menuItemId = $this->menuItem->getId();
        $this->assertEquals($menuItemId, $this->orderItem->getMenuItem()->getId());
    }

    public function testRemove()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->orderItem->setMenuItem($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();
        self::$dm->remove($this->orderItem);
        self::$dm->flush();
        $docOrder = self::$dm->getRepository('RestaurantTablesBundle:OrderItem')->findById($this->orderItem->getId());
        $this->assertEmpty($docOrder);
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