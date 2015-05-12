<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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

    public function testUpdate()
    {
        self::$dm->persist($this->menuItem);
        self::$dm->flush();

        $this->orderItem->setMenuItem($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();

        $orderItems = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\OrderItem')
            ->findAndUpdate()
            ->field("id")->equals($this->orderItem->getId())
            ->field("observations")->set("Sin sal y aceite")
            ->getQuery()->execute();

        foreach ($orderItems as $oi) {
            $this->assertNotEquals($this->orderItem->getObservations(), $oi->getObservations());
        }

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