<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\OrderItem;
use Restaurant\TablesBundle\Document\Order;

class LoadOrdersData extends AbstractFixture implements DependentFixtureInterface
{

    private function loadOrderItems() {

        $orderItem1 = new OrderItem();
        $orderItem1->setDelivered(false);
        $orderItem1->setMenuItem($this->getReference('lomo-saltado'));
        $orderItem1->setObservations('Sin cebolla.');
        $this->addReference('orderitem1', $orderItem1);

        $orderItem2 = new OrderItem();
        $orderItem1->setDelivered(false);
        $orderItem2->setMenuItem($this->getReference('aji-gallina'));
        $orderItem2->setObservations('Sin gallina XD.');
        $this->addReference('orderitem2', $orderItem2);

        $orderItem3 = new OrderItem();
        $orderItem1->setDelivered(true);
        $orderItem2->setMenuItem($this->getReference('aji-gallina'));
        $orderItem3->setMenuItem($this->getReference('chupe-camaron'));
        $orderItem3->setObservations('Sin camaron XD.');
        $this->addReference('orderitem3', $orderItem3);
    }

    public function load(ObjectManager $manager)
    {
        $this->loadOrderItems();

        $order1 = new Order();
        $order1->setDate('2015-05-06 14:50');
        $order1->setTable($this->getReference('occupied-table1'));
        $order1->addOrderItem($this->getReference('orderitem1'));
        $order1->setEmployee($this->getReference('scarlet-johanson'));
        $manager->persist($order1);

        $order2 = new Order();
        $order2->setDate('2015-05-06 10:50');
        $order2->setTable($this->getReference('occupied-table2'));
        $order2->addOrderItem($this->getReference('orderitem2'));
        $order2->addOrderItem($this->getReference('orderitem3'));
        $order2->setEmployee($this->getReference('armando-buenas'));
        $manager->persist($order2);

        $order3 = new Order();
        $order3->setDate('2015-05-05');
        $order3->setTable($this->getReference('occupied-table2'));
        $order3->addOrderItem($this->getReference('orderitem3'));
        $order3->setEmployee($this->getReference('scarlet-johanson'));
        $order3->setActive(false);
        $manager->persist($order3);

        $manager->flush();

        $this->addReference('order1', $order1);
        $this->addReference('order2', $order2);
        $this->addReference('order3', $order2);
    }

    public function getDependencies()
    {
        return array('Restaurant\TablesBundle\DataFixtures\MongoDB\LoadTablesData',
            'Restaurant\TablesBundle\DataFixtures\MongoDB\LoadMenuItemsData',
            'Restaurant\CashBundle\DataFixtures\MongoDB\LoadEmployeesData');
    }
}