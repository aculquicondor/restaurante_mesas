<?php

namespace Restaurant\TablesBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadOrdersData;
use Restaurant\TablesBundle\Tests\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\Repository\OrderRepository;
use Restaurant\TablesBundle\Document\Order;

class OrderControllerTest extends WebTestCase {

    /**
     * @var LoadOrdersData
     */
    private $orderFixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
        $loader = new Loader();
        $this->orderFixture    = new LoadOrdersData();
        $loader->addFixture($this->orderFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/api/orders.json');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($content['orders']));
    }

    public function testGetAllActive()
    {
        $client = static::createClient();

        $client->request('GET', '/api/orders.json?active=1');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(2, count($content['orders']));
    }

    public function testGetOne()
    {
        $client = static::createClient();

        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        $route = '/api/orders/'.$order->getId().'.json';
        $client->request('GET', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);

        $this->assertEquals($order->getId(), $content['id']);
        $this->assertEquals($order->getDate(), new \DateTime($content['date']));
        $this->assertEquals($order->getActive(), $content['active']);
    }

    public function testPost()
    {
        $client = static::createClient();

        /** @var OrderRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Order');
        $url = '/api/orders.json';

        $employee = $this->orderFixture->getReference('scarlet-johanson');
        $table = $this->orderFixture->getReference('table-reserve-now');
        $orderData = array('date' => new \DateTime('now'),
            'table' => $table->getId(),
            'employee' => $employee->getId()
        );
        $client->request('POST', $url, $orderData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $order = json_decode($response->getContent(), true);
        $this->assertEquals($orderData['date'], new \DateTime($order['date']));

        $this->assertNotNull($repository->find($order['id']));
        $this->assertEquals(4, count($repository->findAll()));
    }

    public function testDelete()
    {
        $client = static::createClient();
        /** @var OrderRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Order');

        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        $route = '/api/orders/' . $order->getId() . '.json';
        $client->request('DELETE', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($repository->findAll()));
        $this->assertNull($other = $repository->find($order->getId()));
    }

    public function testPatch()
    {
        $client = static::createClient();
        /** @var OrderRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Order');

        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        $route = '/api/orders/' . $order->getId() . '.json';
        $orderData = array('date' => new \DateTime('now'));
        $client->request('PATCH', $route, $orderData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $received = json_decode($response->getContent(), true);
        $this->assertEquals($order->getDate(), new \DateTime($received['date']));

        $stored = $repository->find($order->getId());
        $this->assertEquals($order->getDate(), $stored->getDate());
    }

}
