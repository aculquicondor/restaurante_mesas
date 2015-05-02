<?php

namespace Restaurant\AuthBundle\Tests\Document;

use Restaurant\TablesBundle\Tests\KernelTestCase;
use Restaurant\AuthBundle\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;

class UserTest extends KernelTestCase {

    /**
     * @var DocumentManager
     */
    private static $dm;

    /**
     * @var User
     */
    private $user;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$dm = static::$kernel->getContainer()
            ->get('platform.user.manager');
    }

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->user = new User;
        $this->user->setUsername('testuser');
        $this->user->setPassword('testpassword');
        $this->user->setEmail('email@test.test');
    }

    public function testPersistence()
    {
        self::$dm->persist($this->user);
        self::$dm->flush();
        $this->assertNotNull($this->user->getId());

        $repository = self::$dm->getRepository('RestaurantAuthBundle:User');
        $f_user = $repository->find($this->user->getId());
        $this->assertEquals($this->user, $f_user);

        self::$dm->remove($this->user);
        self::$dm->flush();

        $this->assertNull($repository->find($this->user->getId()));
    }

    public function testRoles()
    {
        $role = 'ROLE_TEST';
        $this->user->addRole($role);
        $this->assertContains($role, $this->user->getRoles());
        $this->user->removeRole($role);
        $this->assertNotContains($role, $this->user->getRoles());
    }

    public function testSerialization()
    {
        $serialized = $this->user->serialize();
        $d_user = new User;
        $d_user->unserialize($serialized);

        $this->assertEquals(
            $this->user->getId(),$d_user->getId());
        $this->assertEquals(
            $this->user->getUsername(), $d_user->getUsername());
        $this->assertEquals(
            $this->user->getPassword(), $d_user->getPassword());
        $this->assertEquals(
            $this->user->getSalt(), $d_user->getSalt());
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$dm->close();
    }
}