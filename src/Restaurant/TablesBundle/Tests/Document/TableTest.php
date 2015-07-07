<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Document\Table;
use Doctrine\ODM\MongoDB\DocumentManager;


class TableTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private static $dm;

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
        $this->table = new Table();
        $this->table->setNumber(1);
        $this->table->setOccupationTime("2015-05-13 20:00:00");
        $this->table->setCapacity(2);
        $this->table->setAvailable(true);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->table);
        self::$dm->flush();
        $this->assertNotNull($this->table->getId());
    }

    public function testUpdateNumber()
    {
        $oldNumber = $this->table->getNumber();
        $newNumber = 2;

        self::$dm->persist($this->table);
        self::$dm->flush();

        $this->table->setNumber($newNumber);
        $docTable = self::$dm->getRepository("RestaurantTablesBundle:Table")
            ->find($this->table->getId());
        $this->assertNotEquals($oldNumber, $docTable->getCapacity());
    }

    public function testUpdateCapacity()
    {
        $oldCapacity = $this->table->getCapacity();
        $newCapacity = 4;

        self::$dm->persist($this->table);
        self::$dm->flush();

        $this->table->setCapacity($newCapacity);
        $docTable = self::$dm->getRepository("RestaurantTablesBundle:Table")->find($this->table->getId());
        $this->assertNotEquals($oldCapacity, $docTable->getCapacity());
    }

    public function testUpdateAvailable()
    {
        $oldAvailable = $this->table->getAvailable();
        $newAvailable = false;

        self::$dm->persist($this->table);
        self::$dm->flush();

        $this->table->setAvailable($newAvailable);
        $docTable = self::$dm->getRepository("RestaurantTablesBundle:Table")->find($this->table->getId());
        $this->assertNotEquals($oldAvailable, $docTable->getAvailable());
    }

    public function testUpdateOccupationTime()
    {
        $oldOccupationTime = $this->table->getOccupationTime();
        $newOccupationTime = "2015-05-13 23:00:00";

        self::$dm->persist($this->table);
        self::$dm->flush();

        $this->table->setOccupationTime($newOccupationTime);
        $docTable = self::$dm->getRepository("RestaurantTablesBundle:Table")->find($this->table->getId());
        $this->assertNotEquals($oldOccupationTime, $docTable->getOccupationTime());
    }

    public function testRemove()
    {
        self::$dm->persist($this->table);
        self::$dm->flush();

        self::$dm->remove($this->table);
        self::$dm->flush();

        $docTable = self::$dm->getRepository('RestaurantTablesBundle:Table')->findById($this->table->getId());
        $this->assertEmpty($docTable);
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