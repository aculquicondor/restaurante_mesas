<?php

namespace Restaurant\AuthBundle\Provider;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;


class UserProvider implements UserProviderInterface
{

    protected $userRepository;

    public function  __construct(ObjectRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        try {
            $user = $this->userRepository->findOneByUsername($username);
            if (is_null($user))
                throw new DocumentNotFoundException;
        } catch (DocumentNotFoundException $e) {
            $message = sprintf(
                'Unable to find an active user object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', $class)
            );
        }
        return $this->userRepository->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->userRepository->getClassName() === $class ||
            is_subclass_of($class, $this->userRepository->getClassName());
    }

}