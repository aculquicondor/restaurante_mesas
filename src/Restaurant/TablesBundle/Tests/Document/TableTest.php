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
        $this->table->setOccupationTime(new \DateTime());
        $this->table->setCapacity(2);
        $this->table->setAvailable(true);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->table);
        self::$dm->flush();
        $this->assertNotNull($this->table->getId());
    }

    public function testUpdate()
    {
        self::$dm->persist($this->table);
        self::$dm->flush();

        $tables = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Table')
            ->findAndUpdate()
            ->field("id")->equals($this->table->getId())
            ->field("capacity")->set(4)
            ->getQuery()->execute();
        foreach($tables as $t)
        {
            $this->assertNotEquals($this->table->getCapacity(), $t->getCapacity());
        }

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