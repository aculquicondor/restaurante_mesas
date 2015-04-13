<?php

namespace Restaurant\TablesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RestaurantTablesBundle:Default:index.html.twig', array('name' => $name));
    }
}
