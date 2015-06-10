<?php

namespace Restaurant\CashBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\CashBundle\Document\Employee;


class LoadEmployeesData extends AbstractFixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $employee1 = new Employee();
        $employee1->setName('Armando Buenas');
        $employee1->setDni('73708839');
        $manager->persist($employee1);

        $employee2 = new Employee();
        $employee2->setName('Alejandro Manuel');
        $employee2->setDni('76602312');
        $manager->persist($employee2);

        $employee3 = new Employee();
        $employee3->setName('Ortencia Flores');
        $employee3->setDni('47657789');
        $manager->persist($employee3);

        $employee4 = new Employee();
        $employee4->setName('Scarlet Johanson');
        $employee4->setDni('98767897');
        $manager->persist($employee4);

        $manager->flush();

        $this->addReference('armando-buenas', $employee1);
        $this->addReference('alejandro-manuel', $employee2);
        $this->addReference('ortencia-flores', $employee3);
        $this->addReference('scarlet-johanson', $employee4);
    }
}