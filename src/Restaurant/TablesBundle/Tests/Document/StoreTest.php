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
        $this->store = new Store();
        $this->store->setAddress("Av. Del Parque Sur 565");

        $this->employee = new Employee();
        $this->employee->setDni("10203040");
        $this->employee->setName("Admin Carlitos Way");

    }

    public function testPersistence()
    {
        self::$dm->persist($this->employee);
        self::$dm->flush();

        $this->store->setManager($this->employee);

        self::$dm->persist($this->store);
        self::$dm->flush();
        $this->assertNotNull($this->store->getId());
    }

    public function testUpdateAddress()
    {
        $oldAddress = $this->store->getAddress();
        $newAddress = "Av. Larco 500";
        self::$dm->persist($this->employee);
        self::$dm->flush();

        $this->store->setManager($this->employee);
        self::$dm->persist($this->store);
        self::$dm->flush();

        $this->store->setAddress($newAddress);
        $docStore = self::$dm->getRepository("RestaurantTablesBundle:Store")->find($this->store->getId());
        $this->assertNotEquals($oldAddress, $docStore->getAddress());
    }

    public function testManager()
    {
        self::$dm->persist($this->employee);
        self::$dm->flush();
        $managerId = $this->employee->getId();

        $this->store->setManager($this->employee);
        self::$dm->persist($this->store);
        self::$dm->flush();

        $docStore = self::$dm->getRepository("RestaurantTablesBundle:Store")->find($this->store->getId());
        $this->assertEquals($managerId, $docStore->getManager()->getId());
    }

    public function testRemove()
    {
        self::$dm->persist($this->employee);
        self::$dm->flush();

        $this->store->setManager($this->employee);
        self::$dm->persist($this->store);
        self::$dm->flush();

        self::$dm->remove($this->store);
        self::$dm->flush();
        $docStore = self::$dm->getRepository('RestaurantTablesBundle:Store')->findById($this->store->getId());
        $this->assertEmpty($docStore);
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
