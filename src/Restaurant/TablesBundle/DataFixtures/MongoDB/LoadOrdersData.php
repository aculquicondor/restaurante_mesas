<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\Order;

class LoadOrdersData extends AbstractFixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $order = new Order();
        $order->setDate('2015-05-06');
        $order->setTable($this->getReference('occupied-table1'));
        $order->addOrderItem($this->getReference('order-lomo-saltado'));
        $manager->persist($order);

        $order = new Order();
        $order->setDate('2015-05-08');
        $order->setTable($this->getReference('occupied-table2'));
        $order->addOrderItem($this->getReference('order-aji-gallina'));
        $manager->persist($order);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array('Restaurant\TablesBundle\DataFixtures\MongoDB\LoadOrderItemsData',
            'Restaurant\TablesBundle\DataFixtures\MongoDB\LoadTablesData'
        );

    }
}