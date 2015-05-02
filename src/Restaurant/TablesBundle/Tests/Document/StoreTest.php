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
            ->get('doctrine_mongodb')->getManager();
    }

    /**
     * @var inheritDoc
     */
    public function setUp()
    {
        $this->store = new Store();
        $this->store->setAddress("Av. Del Parque Sur 565");
    }

    public function testPersistence()
    {
        $this->employee = new Employee();
        $this->employee->setDni("10203040");
        $this->employee->setName("Admin Carlitos Way");
        $this->store->setManager($this->employee);

        self::$dm->persist($this->store);
        self::$dm->flush();
        $this->assertNotNull($this->store->getId());
    }

    public function testUpdate()
    {
        $stores = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Store')
            ->findAndUpdate()
            ->field("address")->equals("Av. Del Parque Sur 565")
            ->field("address")->set("Av. Larco 500")
            ->getQuery()->execute();
        foreach($stores as $s)
        {
            $this->assertNotEquals($this->store->getAddress(), $s->getAddress());
        }
    }
    public function testRemove()
    {
        $stores = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Store')
            ->find()
            ->field("address")->equals("Av. Larco 500")
            ->getQuery()->execute();
        foreach($stores as $s)
        {
            $obj = self::$dm->remove($s);
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
