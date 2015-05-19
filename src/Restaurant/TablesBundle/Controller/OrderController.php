<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{

    /**
     * @param Request request
     * @return \Symfony\Component\Form\FormErrorIterator|Order
     * @View()
     */

    public function postOrderAction(Request $request)
    {

    }

    /**
     * @param $id
     * @return Order
     * @throws NotFoundHttpException
     * @View()
     */
    public function getOrderAction($id)
    {

    }

    /**
     * @param $id
     * @return array()
     * @throws NotFoundHttpException
     * @View()
     */
    public function deleteOrderAction($id)
    {

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|Order
     * @throws NotFoundHttpException
     * @View()
     */
    public function patchOrderAction(Request $request, $id)
    {

    }
}