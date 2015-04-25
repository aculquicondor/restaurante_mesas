<?php

namespace Restaurant\AuthBundle\Tests\Provider;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\AuthBundle\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;


class UserProviderTest extends KernelTestCase
{

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        self::bootKernel();
        $this->dm = static::$kernel->getContainer()
            ->get('platform.user.manager');
        $this->assertNotNull($this->dm);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadByUsername()
    {
        $user = new User;
        $user->setUsername('testuser');
        $user->setPassword('testpassword');
        $this->dm->persist($user);
        $this->dm->flush();

        $user_provider = static::$kernel->getContainer()
            ->get('platform.user.provider');

        $found_user = $user_provider
            ->loadUserByUsername($user->getUsername());
        $this->assertEquals($user, $found_user);

        $this->dm->remove($user);
        $this->dm->flush();

        $user_provider->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritDoc
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->dm->close();
    }
}