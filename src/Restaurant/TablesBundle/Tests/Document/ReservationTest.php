<?php

namespace Restaurant\TablesBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\TablesBundle\Document\Reservation;
use Restaurant\CashBundle\Document\Client;
use Doctrine\ODM\MongoDB\DocumentManager;

class ReservationTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private static $dm;

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * @var Client
     */
    private $client;

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
        $this->reservation = new Reservation();
        $this->reservation->setDate(new \MongoDate(strtotime("2015-05-02 22:00:00")));
        $this->reservation->setEstimatedTime(new \MongoTimestamp(strtotime("2015-05-02 23:50:00")));
    }

    public function testPersistence()
    {
        $this->client = new Client();
        $this->client->setName("Astro Boy");
        $this->client->setDni("40506070");
        $this->client->setRuc("10405060701");
        $this->client->setAddress("Av. Benavides 201");

        $this->reservation->setClient($this->client);
        self::$dm->persist($this->reservation);
        self::$dm->flush();
        $this->assertNotNull($this->reservation->getId());
    }

    public function testUpdate()
    {
        $reservations = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Reservation')
            ->findAndUpdate()
            ->field("estimated_time")->lte(new \MongoTimestamp(strtotime("2015-05-02 23:50:00")))
            ->field("estimated_time")->set(new \MongoTimestamp(strtotime("2015-05-03 01:00:00")))
            ->getQuery()->execute();
        foreach ($reservations as $r) {
            $this->assertNotEquals($this->reservation->getEstimatedTime(), $r->getEstimatedTime());
        }

    }
    public function testRemove()
    {
        $reservations = self::$dm->createQueryBuilder('\Restaurant\TablesBundle\Document\Reservation')
            ->find()
            ->field("estimated_time")->gte(new \MongoDate(strtotime("2015-05-03 01:00:00")))
            ->getQuery()->execute();
        foreach($reservations as $r)
        {
            $obj = self::$dm->remove($r);
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