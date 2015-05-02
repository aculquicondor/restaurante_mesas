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
     * @var inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
    }

    /**
     * @var inheritDoc
     */
    public function setUp()
    {
        $this->orderItem = new OrderItem();
        $this->orderItem->setObservations("Sin sal");
    }

    public function testPersistence()
    {
        $this->menuItem = new MenuItem();
        $this->menuItem->setName("Rocoto");
        $this->menuItem->setPrice(0.50);
        $this->menuItem->setAvailable(true);

        $this->orderItem->setMenuItem($this->menuItem);
        self::$dm->persist($this->orderItem);
        self::$dm->flush();
        $this->assertNotNull($this->orderItem->getId());
    }

    public function testUpdate()
    {
        $orderItems = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\OrderItem')
            ->findAndUpdate()
            ->field("observations")->equals("Sin sal")
            ->field("observations")->set("Sin sal y aceite")
            ->getQuery()->execute();
        foreach ($orderItems as $oi) {
            $this->assertNotEquals($this->orderItem->getObservations(), $oi->getObservations());
        }

    }
    public function testRemove()
    {
        $orderItems = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\OrderItem')
            ->find()
            ->field("observations")->equals("Sin sal y aceite")
            ->getQuery()->execute();
        foreach($orderItems as $oi)
        {
            $obj = self::$dm->remove($oi);
            $this->assertNull($obj);
        }
        self::$dm->flush();
    }

    /**
     * @var inheritDoc
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$dm->close();
    }
}