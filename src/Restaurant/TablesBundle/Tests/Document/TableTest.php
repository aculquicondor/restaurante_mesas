<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use \Restaurant\TablesBundle\Document\Table;
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
        $this->table = new Table();
        $this->table->setOccupationTime(20);
        $this->table->setCapacity(2);
        $this->table->setAvailable(true);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->table);
        self::$dm->flush();
    }

    public function testUpdate()
    {
        $tables = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Table')
            ->findAndUpdate()
            ->field("capacity")->lte(3)
            ->field("capacity")->set(4)
            ->getQuery()->execute();
        foreach($tables as $t)
        {
            $this->assertNotEquals($this->table->getCapacity(), $t->getCapacity());
        }

    }


    public function testRemove()
    {
        $tables = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Table')
            ->find()
            ->field("capacity")->equals(4)
            ->getQuery()->execute();
        foreach($tables as $t)
        {
            $obj = self::$dm->remove($t);
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