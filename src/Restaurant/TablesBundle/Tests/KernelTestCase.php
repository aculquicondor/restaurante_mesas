<?php

namespace Restaurant\TablesBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;


/**
 * @inheritDoc
 */
abstract class KernelTestCase extends BaseKernelTestCase {

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

}
