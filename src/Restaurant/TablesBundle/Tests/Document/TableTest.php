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
        $this->table->setOccupationTime(30);
        $this->table->setCapacity(4);
        $this->table->setAvailable(true);
    }

    public function testPersistence()
    {
        self::$dm->persist($this->table);
        self::$dm->flush();
    }

    public function testRemove()
    {
        self::$dm->remove($this->table);
        $this->assertNull($this->table->getId());
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