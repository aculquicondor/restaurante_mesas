<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Document\Order;
use Restaurant\CashBundle\Document\Employee;
use Restaurant\TablesBundle\Document\Table;
use Restaurant\TablesBundle\Document\OrderItem;
use Restaurant\TablesBundle\Document\MenuItem;
use Doctrine\ODM\MongoDB\DocumentManager;


class OrderTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */

    private static $dm;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Employee
     */
    private $employee;

    /**
     * @var Table
     */
    private $table;

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
        $this->order = new Order();
        $this->order->setDate('2015-04-29 10:10');

        $this->employee = new Employee();
        $this->employee->setDni("20304050");
        $this->employee->setName("Mozo Carlitos Way");

        $this->table = new Table();
        $this->table->setAvailable(true);
        $this->table->setCapacity(4);
        $this->table->setOccupationTime(new \DateTime());
    }

    public function testPersistence()
    {
        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);

        self::$dm->persist($this->order);
        self::$dm->flush();
        $this->assertNotNull($this->order->getId());
    }

    public function testUpdateDate()
    {

        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();
        $oldDate = $this->order->getDate();
        $newDate = "2015-05-01 00:00:00";

        $this->order->setDate($newDate);
        $docOrder = self::$dm->getRepository("RestaurantTablesBundle:Order")->find($this->order->getId());
        $this->assertNotEquals($oldDate, $docOrder->getDate());
    }

    public function testTable()
    {

        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();
        $tableId = $this->table->getId();

        $this->assertEquals($tableId, $this->order->getTable()->getId());
    }

    public function testEmployee()
    {

        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();
        $employeeId = $this->employee->getId();
        $this->assertEquals($employeeId, $this->order->getEmployee()->getId());
    }

    public function testActive()
    {

        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();

        $oldActive = $this->order->getActive();
        $this->order->setActive(false);
        $docOrder = self::$dm->getRepository("RestaurantTablesBundle:Order")->find($this->order->getId());
        $this->assertNotEquals($oldActive, $docOrder->getActive());
    }

    public function testAddOrderItem()
    {
        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();
        $oldOrderItems = $this->order->getOrderItems()->isEmpty();

        $menuItem = new MenuItem();
        $menuItem->setAvailable(true);
        $menuItem->setName("Café");
        $menuItem->setPrice(5.87);
        self::$dm->persist($menuItem);

        $orderItem = new OrderItem();
        $orderItem->setMenuItem($menuItem);
        $orderItem->setObservations("Poca sal");
        self::$dm->persist($orderItem);

        $this->order->addOrderItem($orderItem);
        $docOrder = self::$dm->getRepository("RestaurantTablesBundle:Order")->find($this->order->getId());
        $this->assertNotEquals($oldOrderItems, $docOrder->getOrderItems()->isEmpty());
    }


    public function testRemove()
    {
        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);
        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();

        self::$dm->remove($this->order);
        self::$dm->flush();
        $docOrder = self::$dm->getRepository('RestaurantTablesBundle:Order')->findById($this->order->getId());
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
