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
        $order = new Order();
        $form = $this->createForm(new OrderType());
        $form -> submit($request->request->all());
        if($form->isValid())
        {
            $date = $request->request->get('date');
            $orderItems = $request->request->get('orderItems');
            $employee = $request->request->get('employee');
            $table = $request->request->get('table');
            $dm = $this->get('doctrine_mongodb')->getManager();
            $order->setDate($date);
            $docEmployee = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('RestaurantCashBundle:Employee')
                ->findOneById($employee);
            $order->setEmployee($docEmployee);
            $docTable = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('RestaurantTablesBundle:Table')
                ->findOneById($table);
            $order->setTable($docTable);
//            $order->addOrderItem($orderItems);
            $order->setActive(true);
            $dm->persist($order);
            $dm->flush();
            return $order;
        }
        return $form->getErrors();
    }

    /**
     * @return Collection Order
     * @throws NotFoundHttpException
     * @View()
     */
    public function getOrdersAction()
    {
        $orders = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Order')
            ->findAll();
        if(!$orders)
            throw new NotFoundHttpException();
        return $orders;
    }

    /**
     * @param $id
     * @return Order
     * @throws NotFoundHttpException
     * @View()
     */
    public function getOrderAction($id)
    {
        $order = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Order')
            ->findOneById($id);
        if(!$order)
            throw new NotFoundHttpException();
        return $order;
    }

    /**
     * @param $id
     * @return array()
     * @throws NotFoundHttpException
     * @View()
     */
    public function deleteOrderAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $order = $dm->getRepository('RestaurantTablesBundle:Order')->findOneById($id);
        if(is_null($order))
            throw new NotFoundHttpException();
        $dm->remove($order);
        $dm->flush();
        return array();
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
        $dm = $this->get('doctrine_mongodb')->getManager();
        $order = $dm->getRepository('RestaurantTablesBundle:Order')->findOneById($id);
        if(is_null($order))
            throw new NotFoundHttpException();
        $form = $this->createForm(new OrderType());
        $form->submit($request->request->all());
        if($form->isValid())
        {
            $date = $request->request->get('date');
            $orderItems = $request->request->get('orderItems');
            $employee = $request->request->get('employee');
            $table = $request->request->get('table');
            if(!is_null($date))
            {
                $order->setDate($date);
            }
            if(!is_null($orderItems))
            {
                $order->addOrderItem($orderItems);
            }
            if(!is_null($employee))
            {
                $order->setEmployee($employee);
            }
            if(!is_null($table))
            {
                $order->setTable($table);
            }
            $dm->flush();
            return $order;
        }
        return $form->getErrors();
    }
}