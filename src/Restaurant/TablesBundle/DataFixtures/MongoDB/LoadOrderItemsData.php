<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\OrderItem;

class LoadOrderItemsData extends AbstractFixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $orderItem1 = new OrderItem();
        $orderItem1->setMenuItem($this->getReference('lomo-saltado'));
        $orderItem1->setObservations('Sin cebolla.');
        $manager->persist($orderItem1);


        $orderItem2 = new OrderItem();
        $orderItem2->setMenuItem($this->getReference('aji-gallina'));
        $orderItem2->setObservations('Sin gallina XD.');
        $manager->persist($orderItem2);


        $orderItem3 = new OrderItem();
        $orderItem3->setMenuItem($this->getReference('chupe-camaron'));
        $orderItem3->setObservations('Sin camaron XD.');
        $manager->persist($orderItem3);

        $manager->flush();

        $this->addReference('order-lomo-saltado', $orderItem1);
        $this->addReference('order-aji-gallina', $orderItem2);
        $this->addReference('order-chupe-camaron', $orderItem3);

    }

    public function getDependencies()
    {
        return array('Restaurant\TablesBundle\DataFixtures\MongoDB\LoadMenuItemsData');
    }
}