<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\MenuItem;


class LoadMenuItemsData extends AbstractFixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $menuItem1 = new MenuItem();
        $menuItem1->setName('Lomo Saltado');
        $menuItem1->setPrice(25.00);
        $menuItem1->setAvailable(true);
        $manager->persist($menuItem1);

        $menuItem2 = new MenuItem();
        $menuItem2->setName('Aji de Gallina');
        $menuItem2->setPrice(20.00);
        $menuItem2->setAvailable(true);
        $manager->persist($menuItem2);

        $menuItem3 = new MenuItem();
        $menuItem3->setName('Chupe de Camaron');
        $menuItem3->setPrice(23.50);
        $menuItem3->setAvailable(true);
        $manager->persist($menuItem3);

        $menuItem4 = new MenuItem();
        $menuItem4->setName('Causa de Pollo');
        $menuItem4->setPrice(15.50);
        $menuItem4->setAvailable(false);
        $manager->persist($menuItem4);

        $manager->flush();

        $this->addReference('lomo-saltado', $menuItem1);
        $this->addReference('aji-gallina', $menuItem2);
        $this->addReference('chupe-camaron', $menuItem3);
        $this->addReference('causa-pollo', $menuItem4);

    }

}