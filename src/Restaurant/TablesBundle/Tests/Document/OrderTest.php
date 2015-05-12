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

    public function testUpdate()
    {

        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);

        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();

        $docOrder = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Order')
            ->findAndUpdate('\Restaurant\TablesBundle\Document\Order')
            ->field("id")->equals($this->order->getId())
            ->field("date")->set(new \MongoDate(strtotime("2015-05-01 00:00:00")))
            ->getQuery()->execute();
        foreach($docOrder as $o)
        {
            $this->assertNotEquals($this->order->getDate(), $o->getDate());
        }
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
