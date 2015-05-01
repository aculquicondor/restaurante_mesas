<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Document\Order;
use Restaurant\CashBundle\Document\Employee;
use Restaurant\TablesBundle\Document\Table;
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
     * @var employeeDni
     */
    private $employeeDni;

    /**
     * @var Table
     */
    private $table;

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
        $this->order = new Order();
        $this->order->setDate('2014-10-12 10:10');
    }

    public function testPersistence()
    {
        $this->employee = new Employee();
        $this->employee->setDni("20304050");
        $this->employee->setName("Mozo Carlitos Way");

        $this->table = new Table();
        $this->table->setAvailable(true);
        $this->table->setCapacity(4);
        $this->table->setOccupationTime(30);

        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);

        self::$dm->persist($this->order);
        self::$dm->flush();
        $this->assertNotNull($this->order->getId());
    }

    public function testUpdate()
    {
        $orders = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Order')
            ->findAndUpdate()
            ->field("date")->lte(new \MongoDate(strtotime("2014-10-13 00:00:00")))
            ->field("date")->set(new \MongoDate(strtotime("2015-05-01 00:00:00")))
            ->getQuery()->execute();
        foreach($orders as $o)
        {
            $this->assertNotEquals($this->order->getDate(), $o->getDate());
        }
    }

    public function testRemove()
    {
        $orders = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Order')
            ->find()
            ->field("date")->gte(new \MongoDate(strtotime("2015-05-01 00:00:00")))
            ->getQuery()->execute();
        foreach($orders as $o)
        {
            $obj = self::$dm->remove($o);
            self::$dm->flush();
            $this->assertNull($obj);
        }
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
