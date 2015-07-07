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

    public function testGetOne()
    {
        $client = static::createClient();
        /** @var $order Order */
        /** @var $orderItem OrderItem */
        $order = $this->orderFixture->getReference('order1');
        $orderItem = $this->orderFixture->getReference('orderitem1');
        $route = '/api/orders/'.$order->getId().'/items/'.$orderItem->getId().'.json';
        $client->request('GET', $route);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($orderItem->getId(), $content['id']);
    }

    public function testPost()
    {
        $client = static::createClient();
        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        /** @var OrderRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Order');
        $route = '/api/orders/' . $order->getId() . '/items';
        $menuItem = $this->orderFixture->getReference('aji-gallina');
        $orderItemData = array(
            'menu_item' => $menuItem->getId(),
            'observations' => 'Sin gallina.',
            'delivered' => false
        );
        $client->request('POST', $route, $orderItemData);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $orderItem = json_decode($response->getContent(), true);
        $this->assertEquals($orderItemData['observations'], $orderItem['observations']);
        $docOrder = $repository->find($order->getId());
        $this->assertEquals(2, count($docOrder->getOrderItems()));
    }

    public function testDelete()
    {
        $client = static::createClient();
        /** @var OrderRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Order');

        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        /** @var @var $orderItem OrderItem */
        $orderItem = $this->orderFixture->getReference('orderitem1');
        $itemRoute = '/api/orders/' . $order->getId() . '/items/' . $orderItem->getId();
        $client->request('DELETE', $itemRoute);
        $response = $client->getResponse();

        $docOrder = $repository->find($order->getId());
        $this->assertEquals(200, $response->getStatusCode());
        $client->request('GET', '/api/orders' . $order->getId());
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(0, count($content['order_items']));
    }

    public function testPatch()
    {
        $client = static::createClient();
        /** @var OrderRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Order');

        /** @var $order Order */
        $order = $this->orderFixture->getReference('order1');
        /** @var $orderItem OrderItem */
        $orderItem = $this->orderFixture->getReference('orderitem1');
        $route = '/api/orders/' . $order->getId() . '/items/' . $orderItem->getId();
        $orderItemData = array('delivered' => true);
        $client->request('PATCH', $route, $orderItemData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals($orderItemData['delivered'], $content['delivered']);
    }
}