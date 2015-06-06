<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\OrderItem;
use Restaurant\TablesBundle\Document\Order;

class LoadOrdersData extends AbstractFixture implements DependentFixtureInterface
{

    private $orderItems = array();

    private function loadOrderItems() {

        $orderItem1 = new OrderItem();
        $orderItem1->setMenuItem($this->getReference('lomo-saltado'));
        $orderItem1->setObservations('Sin cebolla.');
        $this->orderItems['lomo-saltado'] = $orderItem1;

        $orderItem2 = new OrderItem();
        $orderItem2->setMenuItem($this->getReference('aji-gallina'));
        $orderItem2->setObservations('Sin gallina XD.');
        $this->orderItems['aji-gallina'] = $orderItem2;

        $orderItem3 = new OrderItem();
        $orderItem3->setMenuItem($this->getReference('chupe-camaron'));
        $orderItem3->setObservations('Sin camaron XD.');
        $this->orderItems['chupe-camaron'] = $orderItem3;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadOrderItems();

        $order1 = new Order();
        $order1->setDate('2015-05-06');
        $order1->setTable($this->getReference('occupied-table1'));
        $order1->addOrderItem($this->orderItems['lomo-saltado']);
        $manager->persist($order1);

        $order2 = new Order();
        $order2->setDate('2015-05-08');
        $order2->setTable($this->getReference('occupied-table2'));
        $order2->addOrderItem($this->orderItems['aji-gallina']);
        $order2->addOrderItem($this->orderItems['chupe-camaron']);
        $manager->persist($order2);

        $manager->flush();

        $this->addReference('order1', $order1);
        $this->addReference('order2', $order2);
    }

    public function getDependencies()
    {
        return array('Restaurant\TablesBundle\DataFixtures\MongoDB\LoadTablesData',
            'Restaurant\TablesBundle\DataFixtures\MongoDB\LoadMenuItemsData');

    }
}