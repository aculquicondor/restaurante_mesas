<?php

namespace Restaurant\TablesBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\TablesBundle\Document\Table;


class LoadTablesData extends AbstractFixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $table = new Table();
        $table->setAvailable(true);
        $table->setCapacity(4);
        $manager->persist($table);

        $table = new Table();
        $table->setAvailable(false);
        $table->setOccupationTime(new \DateTime('2015-05-06 09:00'));
        $table->setCapacity(4);
        $manager->persist($table);

        $table1 = new Table();
        $table1->setAvailable(true);
        $table1->setCapacity(4);
        $manager->persist($table1);

        $table2 = new Table();
        $table2->setAvailable(true);
        $table2->setCapacity(4);
        $manager->persist($table2);

        $manager->flush();

        $this->addReference('table-reserve-now', $table1);
        $this->addReference('table-reserve-later', $table2);
    }

}