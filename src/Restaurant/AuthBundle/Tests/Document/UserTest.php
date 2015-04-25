<?php

namespace Restaurant\AuthBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Restaurant\AuthBundle\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;

class UserTest extends KernelTestCase {

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

    public function testCreation()
    {
        $user = new User;
        $user->setUsername('testuser');
        $user->setPassword('testpassword');

        $this->dm->persist($user);
        $this->dm->flush();
        $this->assertNotNull($user->getId());

        $this->dm->remove($user);
        $this->dm->flush();

        $repository = $this->dm->getRepository('RestaurantAuthBundle:User');
        $this->assertNull($repository->find($user->getId()));
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