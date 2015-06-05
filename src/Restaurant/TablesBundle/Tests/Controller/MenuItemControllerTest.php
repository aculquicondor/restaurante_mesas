<?php

namespace Restaurant\TablesBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Restaurant\TablesBundle\Tests\WebTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Restaurant\TablesBundle\Repository\MenuItemRepository;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadMenuItemsData;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\Document\MenuItem;


class MenuItemControllerTest extends WebTestCase {

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var MenuItemRepository
     */
    private $menuRepository;

    /**
     * @var LoadMenuItemsData
     */
    private $menuFixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();
        $this->menuRepository = $this->dm
            ->getRepository('RestaurantTablesBundle:MenuItem');

        $loader = new Loader();
        $this->menuFixture = new LoadMenuItemsData();
        $loader->addFixture($this->menuFixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($this->dm, $purger);
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

}