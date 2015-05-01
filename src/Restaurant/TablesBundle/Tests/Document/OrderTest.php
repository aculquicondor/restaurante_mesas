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
     * @var inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$dm = static::$kernel->getContainer()
            ->get('platform.user.manager');
    }

    /**
     * @var inheritDoc
     */
    public function setUp()
    {
        $this->employee = new Employee();
        $this->employee->setDni('10203040');
        $this->employee->setName("Carlitos Way");

        $this->table = new Table();
        $this->table->setAvailable(true);
        $this->table->setCapacity(4);
        $this->table->setOccupationTime(30);

        $this->order = new Order();
        $this->order->setDate('10/12/2015');
        $this->order->setEmployee($this->employee);
        $this->order->setTable($this->table);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->employee);
        self::$dm->persist($this->table);
        self::$dm->persist($this->order);
        self::$dm->flush();
        $this->assertNotNull($this->order->getId());
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
