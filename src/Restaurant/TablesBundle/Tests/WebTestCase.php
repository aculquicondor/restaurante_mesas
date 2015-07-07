<?php

namespace Restaurant\TablesBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;


/*
 * @inheritDoc
 */
abstract class WebTestCase extends BaseWebTestCase {

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();
        $db_connection = $container->get('doctrine_mongodb.odm.default_connection');
        $test_db_name = $container->getParameter('mongodb_test_database');
        $db_connection->dropDatabase($test_db_name);
    }

    /**
     * @inheritDoc
     */
    protected static function createClient(array $options = array(), array $server = array())
    {
        $server['PHP_AUTH_USER'] = 'admin';
        $server['PHP_AUTH_PW'] = 'password';

        static::bootKernel($options);

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

}