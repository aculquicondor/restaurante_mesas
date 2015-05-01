<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Document\Store;
use Restaurant\CashBundle\Document\Employee;
use Doctrine\ODM\MongoDB\DocumentManager;

class StoreTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private static $dm;

    /*
     * @var Store
     */

    private $store;

    /**
     * @var Employee
     */

    private $employee;

    /*
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
        $this->employee->setDni("10203040");
        $this->employee->setName("Carlitos Way");

        $this->store = new Store();
        $this->store->setAddress("Av. Del Parque Sur 565");
        $this->store->setManager($this->employee);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->employee);
        self::$dm->persist($this->store);
        self::$dm->flush();
        $this->assertNotNull($this->store->getId());
    }

    public function testRemove()
    {
        self::$dm->remove($this->employee);
        self::$dm->remove($this->store);
        $this->assertNull($this->store->getId());
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
