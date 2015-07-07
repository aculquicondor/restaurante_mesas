<?php

namespace Restaurant\AuthBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Restaurant\AuthBundle\Document\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersData extends AbstractFixture implements DependentFixtureInterface,
    ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@localhost');
        $user->setEmployee($this->getReference('scarlet-johanson'));

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('password', $user->getSalt()));

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array('Restaurant\CashBundle\DataFixtures\MongoDB\LoadEmployeesData');
    }
}