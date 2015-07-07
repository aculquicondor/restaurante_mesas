<?php

namespace Restaurant\TablesBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Restaurant\TablesBundle\Tests\WebTestCase;
use Restaurant\TablesBundle\Repository\TableRepository;
use Restaurant\TablesBundle\DataFixtures\MongoDB\LoadReservationsData;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Loader;
use Restaurant\TablesBundle\Document\Table;


class TableControllerTest extends WebTestCase {

    /**
     * @var AbstractFixture
     */
    private $fixture;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')->getManager();

        $loader = new Loader();
        $this->fixture = new LoadReservationsData();
        $loader->addFixture($this->fixture);
        $purger = new MongoDBPurger();
        $executor = new MongoDBExecutor($dm, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/api/tables.json');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(5, count($content['tables']));
    }

    public function testGetAllAvailable()
    {
        $client = static::createClient();

        $client->request('GET', '/api/tables.json?available=1');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(3, count($content['tables']));
    }

    public function testGetOne()
    {
        $client = static::createClient();

        /** @var $table Table */
        $table = $this->fixture->getReference('occupied-table1');
        $route = '/api/tables/' . $table->getId() . '.json';
        $client->request('GET', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);

        $this->assertEquals($table->getId(), $content['id']);
        $this->assertEquals($table->getNumber(), $content['number']);
        $this->assertEquals($table->getAvailable(), $content['available']);
        $this->assertEquals($table->getCapacity(), $content['capacity']);
        $this->assertEquals($table->getOccupationTime(), new \DateTime($content['occupation_time']));
    }

    public function testGetReservations()
    {
        $client = static::createClient();

        /** @var $table Table */
        $table = $this->fixture->getReference('table-reserve-later');
        $route = '/api/tables/' . $table->getId() . '/reservations.json';
        $client->request('GET', $route, array('time' => '2015-05-06 09:05'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(1, count($content['reservations']));
    }

    public function testPost()
    {
        $client = static::createClient();
        /** @var TableRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Table');

        $tableData = array('available' => true, 'capacity' => 5);
        $client->request('POST', '/api/tables.json', $tableData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $table = json_decode($response->getContent(), true);
        $this->assertEquals($tableData['available'], $table['available']);
        $this->assertEquals($tableData['capacity'], $table['capacity']);

        $this->assertNotNull($repository->find($table['id']));
        $this->assertEquals(6, count($repository->findAll()));
    }

    public function testDelete()
    {
        $client = static::createClient();
        /** @var TableRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Table');

        /** @var $table Table */
        $table = $this->fixture->getReference('occupied-table2');
        $route = '/api/tables/' . $table->getId() . '.json';
        $client->request('DELETE', $route);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(4, count($repository->findAll()));
        $this->assertNull($other = $repository->find($table->getId()));
    }

    public function testPatch()
    {
        $client = static::createClient();
        /** @var TableRepository $repository */
        $repository = $client->getContainer()->get('doctrine_mongodb')
            ->getManager()->getRepository('RestaurantTablesBundle:Table');

        /** @var $table Table */
        $table = $this->fixture->getReference('occupied-table1');
        $route = '/api/tables/' . $table->getId() . '.json';
        $tableData = array('number' => 6, 'capacity' => 8,
            'occupation_time' => new \DateTime('2015-06-09 11:00'));
        $client->request('PATCH', $route, $tableData);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $received = json_decode($response->getContent(), true);
        $this->assertEquals($table->getId(), $received['id']);
        $this->assertEquals($tableData['number'], $received['number']);
        $this->assertEquals($tableData['capacity'], $received['capacity']);
        $this->assertEquals($tableData['occupation_time'],
            new \DateTime($received['occupation_time']));

        $stored = $repository->find($table->getId());
        $this->assertEquals($table->getId(), $stored->getId());
        $this->assertEquals($tableData['number'], $stored->getNumber());
        $this->assertEquals($tableData['capacity'], $stored->getCapacity());
        $this->assertEquals($tableData['occupation_time'],
            $stored->getOccupationTime());
    }

}
