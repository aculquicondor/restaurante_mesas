<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\Store;


class LoadStoresData extends AbstractFixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $store = new Store();
        $store->setAddress('Calle Pizarro 213, Cercado.');
        $manager->persist($store);

        $store = new Store();
        $store->setAddress('Calle Chancay sin numero, Mariano Melgar.');
        $manager->persist($store);

        $store = new Store();
        $store->setAddress('Calle Peru 403, Cercado.');
        $manager->persist($store);

        $manager->flush();

    }

}
