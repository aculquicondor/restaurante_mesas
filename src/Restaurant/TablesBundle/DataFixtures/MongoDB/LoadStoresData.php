<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\Store;


class LoadStoresData extends AbstractFixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $store1 = new Store();
        $store1->setAddress('Calle Pizarro 213, Cercado.');
        $manager->persist($store1);

        $store2 = new Store();
        $store2->setAddress('Calle Chancay sin numero, Mariano Melgar.');
        $manager->persist($store2);

        $store3 = new Store();
        $store3->setAddress('Calle Peru 403, Cercado.');
        $manager->persist($store3);

        $manager->flush();

        $this->addReference('store1', $store1);
        $this->addReference('store2', $store2);
        $this->addReference('store3', $store3);

    }

    public function getDependencies()
    {
        return array('Restaurant\CashBundle\DataFixtures\MongoDB\LoadEmployeesData');
    }

}
