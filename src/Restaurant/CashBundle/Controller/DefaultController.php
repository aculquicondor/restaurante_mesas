<?php

namespace Restaurant\CashBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CashBundle:Default:index.html.twig', array('name' => $name));
    }
}
