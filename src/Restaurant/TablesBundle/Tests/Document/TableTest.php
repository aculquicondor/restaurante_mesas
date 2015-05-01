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
            ->get('platform.user.manager');
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

    public function testFind(){
        $qb = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Table')
            ->field('capacity')->equals(4);
        $tables = $qb->getQuery()->execute();
        foreach($tables as $table)
        {
            $this->assertEquals(4, $table->getCapacity());
        }
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