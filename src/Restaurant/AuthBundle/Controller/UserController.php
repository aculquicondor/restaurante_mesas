<?php

namespace Restaurant\AuthBundle\Controller;

use Restaurant\AuthBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class UserController extends Controller
{
    /**
     * @return User
     * @ApiDoc(
     *   description="See my user information",
     *   section="Global"
     * )
     * @View()
     */
    public function getMeAction()
    {
        return $this->getUser();
    }
}
