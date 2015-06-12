<?php

namespace Restaurant\TablesBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Restaurant\CashBundle\Repository\EmployeeRepository;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadOrdersData;
use Restaurant\TablesBundle\Tests\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\Repository\OrderRepository;
use Restaurant\TablesBundle\Document\Order;
use Restaurant\TablesBundle\Document\OrderItem;

class OrderItemControllerTest extends WebTestCase
{
    /**
     * var LoadOrdersData
     */
    private $orderFixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
        $loader = new Loader();
        $this->orderFixture = new LoadOrdersData();
        $loader->addFixture($this->orderFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testGetAll()
    {
        $client = static::createClient();
        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        $route = '/api/orders/'.$order->getId().'/items.json';
        $client->request('GET', $route);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(1, count($content['items']));
    }



}