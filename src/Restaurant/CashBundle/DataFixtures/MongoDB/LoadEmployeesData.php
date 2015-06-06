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
        $employee = new Employee();
        $employee->setName('Armando Buenas');
        $employee->setDni('73708839');
        $manager->persist($employee);

        $employee = new Employee();
        $employee->setName('Alejandro Manuel');
        $employee->setDni('76602312');
        $manager->persist($employee);

        $employee = new Employee();
        $employee->setName('Ortencia Flores');
        $employee->setDni('47657789');
        $manager->persist($employee);

        $employee = new Employee();
        $employee->setName('Scarlet Johanson');
        $employee->setDni('98767897');
        $manager->persist($employee);

        $manager->flush();

    }

}