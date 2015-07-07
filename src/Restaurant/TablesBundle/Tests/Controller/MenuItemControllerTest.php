<?php

namespace Restaurant\TablesBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Restaurant\TablesBundle\Tests\WebTestCase;
use Restaurant\TablesBundle\Repository\MenuItemRepository;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadMenuItemsData;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\Document\MenuItem;


class MenuItemControllerTest extends WebTestCase {

    /**
     * @var LoadMenuItemsData
     */
    private $menuFixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();

        $loader = new Loader();
        $this->menuFixture = new LoadMenuItemsData();
        $loader->addFixture($this->menuFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/api/menu/items.json');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(4, count($content['menuItems']));
    }

    public function testGetAllAvailable()
    {
        $client = static::createClient();

        $client->request('GET', '/api/menu/items.json?available=1');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($content['menuItems']));
    }

    public function testGetOne()
    {
        $client = static::createClient();

        /** @var $menuItem MenuItem */
        $menuItem = $this->menuFixture->getReference('lomo-saltado');
        $route = '/api/menu/items/'.$menuItem->getId().'.json';
        $client->request('GET', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);

        $this->assertEquals($menuItem->getId(), $content['id']);
        $this->assertEquals($menuItem->getName(), $content['name']);
        $this->assertEquals($menuItem->getPrice(), $content['price']);
        $this->assertEquals($menuItem->getAvailable(), $content['available']);
    }

    public function testPost()
    {
        $client = static::createClient();
        /** @var MenuItemRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:MenuItem');
        $url = '/api/menu/items.json';

        $menuItemData = array('name' => 'Cebiche', 'price' => 12.5);
        $client->request('POST', $url, $menuItemData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $menuItem = json_decode($response->getContent(), true);
        $this->assertEquals($menuItemData['name'], $menuItem['name']);
        $this->assertEquals($menuItemData['price'], $menuItem['price']);

        $this->assertNotNull($repository->find($menuItem['id']));
        $this->assertEquals(5, count($repository->findAll()));
    }

    public function testDelete()
    {
        $client = static::createClient();
        /** @var MenuItemRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:MenuItem');

        /** @var $menuItem MenuItem */
        $menuItem = $this->menuFixture->getReference('lomo-saltado');
        $route = '/api/menu/items/' . $menuItem->getId() . '.json';
        $client->request('DELETE', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, count($repository->findAll()));
        $this->assertNull($other = $repository->find($menuItem->getId()));
    }

    public function testPatch()
    {
        $client = static::createClient();
        /** @var MenuItemRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:MenuItem');

        /** @var $menuItem MenuItem */
        $menuItem = $this->menuFixture->getReference('lomo-saltado');
        $route = '/api/menu/items/' . $menuItem->getId() . '.json';
        $menuItemData = array('price' => 12.5, 'available' => false);
        $client->request('PATCH', $route, $menuItemData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $received = json_decode($response->getContent(), true);
        $this->assertEquals($menuItem->getName(), $received['name']);
        $this->assertEquals($menuItemData['price'], $received['price']);
        $this->assertEquals($menuItemData['available'], $received['available']);

        $stored = $repository->find($menuItem->getId());
        $this->assertEquals($menuItem->getName(), $stored->getName());
        $this->assertEquals($menuItemData['price'], $stored->getPrice());
        $this->assertEquals($menuItemData['available'], $stored->getAvailable());
    }

}
