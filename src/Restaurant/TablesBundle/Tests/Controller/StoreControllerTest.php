<?php

namespace Restaurant\TablesBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Restaurant\TablesBundle\Tests\WebTestCase;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadStoresData;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\Document\Store;

class StoreControllerTest extends WebTestCase{

    /**
     * @var LoadStoresData
     */
    private $storeFixture;

    public function setUp(){
        parent::setUp();
        self::bootKernel();
        $dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();

        $loader = new Loader();
        $this->storeFixture = new LoadStoresData();
        $loader->addFixture($this->storeFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testPost()
    {
        $client = static::createClient();
        /** @var StoreRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Store');
        $url = '/api/stores.json';

        $manager = $this->storeFixture->getReference('scarlet-johanson');
        $storeData = array('address' => 'Calle Ramon Castilla 213, Cerro Colorado.',
            'manager' => $manager->getId()
        );
        $client->request('POST', $url, $storeData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $store = json_decode($response->getContent(), true);
        $this->assertEquals($storeData['address'], $store['address']);

        $this->assertNotNull($repository->find($store['id']));
        $this->assertEquals(4, count($repository->findAll()));
    }

    public function testDelete()
    {
        $client = static::createClient();
        /** @var StoreRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Store');

        /** @var $store Store */
        $store = $this->storeFixture->getReference('store1');
        $route = '/api/stores/' . $store->getId() . '.json';
        $client->request('DELETE', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($repository->findAll()));
        $this->assertNull($other = $repository->find($store->getId()));
    }

    public function testPatch()
    {
        $client = static::createClient();
        /** @var TableRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Store');

        /** @var $store Store */
        $store = $this->storeFixture->getReference('store1');
        $route = '/api/stores/' . $store->getId() . '.json';
        $storeData = array('address' => 'Calle Ramón Castilla 105, Cerro Colorado.');
        $client->request('PATCH', $route, $storeData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $received = json_decode($response->getContent(), true);
        $this->assertEquals($store->getAddress(), $received['address']);

        $stored = $repository->find($store->getId());
        $this->assertEquals($store->getAddress(), $stored->getAddress());
    }
}