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
        $this->reservation = new Reservation();
        $this->reservation->setEstimatedArrivalTime(new \DateTime("2015-05-02 23:50:00"));

        $this->client = new Client();
        $this->client->setName("Astro Boy");
        $this->client->setDni("40506070");
        $this->client->setRuc("10405060701");
        $this->client->setAddress("Av. Benavides 201");
    }

    public function testPersistence()
    {
        self::$dm->persist($this->client);
        self::$dm->flush();

        $this->reservation->setClient($this->client);
        self::$dm->persist($this->reservation);
        self::$dm->flush();
        $this->assertNotNull($this->reservation->getId());
    }

    public function testUpdateEstimatedArrivalTime()
    {
        self::$dm->persist($this->client);
        self::$dm->flush();

        $this->reservation->setClient($this->client);
        self::$dm->persist($this->reservation);
        self::$dm->flush();

        $oldETA = $this->reservation->getEstimatedArrivalTime();
        $newETA = "2015-05-03 01:00:00";
        $this->reservation->setEstimatedArrivalTime($newETA);
        $docReservation = self::$dm->getRepository("RestaurantTablesBundle:Reservation")->find($this->reservation->getId());
        $this->assertNotEquals($oldETA, $docReservation->getEstimatedArrivalTime());
    }

    public function testClient()
    {
        self::$dm->persist($this->client);
        self::$dm->flush();

        $this->reservation->setClient($this->client);
        self::$dm->persist($this->reservation);
        self::$dm->flush();

        $clientId = $this->client->getId();
        $docReservation = self::$dm->getRepository("RestaurantTablesBundle:Reservation")->find($this->reservation->getId());
        $this->assertEquals($clientId, $docReservation->getClient()->getId());
    }

    public function testRemove()
    {
        self::$dm->persist($this->client);
        self::$dm->flush();

        $this->reservation->setClient($this->client);
        self::$dm->persist($this->reservation);
        self::$dm->flush();

        self::$dm->remove($this->reservation);
        self::$dm->flush();
        $docOrder = self::$dm->getRepository('RestaurantTablesBundle:Reservation')->findById($this->reservation->getId());
        $this->assertEmpty($docOrder);
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