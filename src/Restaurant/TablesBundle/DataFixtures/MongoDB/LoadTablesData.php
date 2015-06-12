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
        $table1 = new Table();
        $table1->setNumber(1);
        $table1->setAvailable(true);
        $table1->setCapacity(4);
        $manager->persist($table1);

        $table2 = new Table();
        $table2->setNumber(2);
        $table2->setAvailable(false);
        $table2->setOccupationTime(new \DateTime('2015-05-06 09:00'));
        $table2->setCapacity(4);
        $manager->persist($table2);

        $table3 = new Table();
        $table3->setNumber(3);
        $table3->setAvailable(false);
        $table3->setOccupationTime(new \DateTime('2015-05-08 10:00'));
        $table3->setCapacity(4);
        $manager->persist($table3);

        $table4 = new Table();
        $table4->setNumber(4);
        $table4->setAvailable(true);
        $table4->setCapacity(4);
        $manager->persist($table4);

        $table5 = new Table();
        $table5->setNumber(5);
        $table5->setAvailable(true);
        $table5->setCapacity(4);
        $manager->persist($table5);

        $manager->flush();

        $this->addReference('table-reserve-now', $table4);
        $this->addReference('table-reserve-later', $table5);
        $this->addReference('occupied-table1', $table2);
        $this->addReference('occupied-table2', $table3);

    }

}