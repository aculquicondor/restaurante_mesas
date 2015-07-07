<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class OrderController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|Order
     * @ApiDoc(
     *   description="Create an order",
     *   section="Order",
     *   parameters={
     *     {"name"="date", "dataType"="string", "required"=false, "description"="The date of the order"},
     *     {"name"="employee", "dataType"="string", "required"=false, "description"="The employee who took the order"},
     *     {"name"="table", "dataType"="string", "required"=false, "description"="The table where the order was taken"},
     *   }
     * )
     * @View()
     */

    public function postOrderAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(new OrderType());
        $form -> submit($request->request->all());
        if($form->isValid())
        {
            $date = $request->request->get('date', 'now');
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
            $order->setActive(true);
            $dm->persist($order);
            $dm->flush();
            return $order;
        }
        return $form->getErrors();
    }

    /**
     * @param Request $request
     * @return array
     * @throws NotFoundHttpException
     * @ApiDoc(
     *   description="View all the orders",
     *   section="Order",
     *   parameters={
     *     {"name"="employee", "dataType"="string", "required"=false, "description"="The employee who took the order"},
     *     {"name"="active", "dataType"="boolean", "required"=false, "description"="See only not finished orders"}
     *   }
     * )
     * @View()
     */
    public function getOrdersAction(Request $request)
    {
        $active = $request->get('active');
        $employeeId = $request->get('employee');
        $dm = $this->get('doctrine_mongodb')->getManager();
        $employee = $employeeId != null ?
            $dm->getRepository('RestaurantCashBundle:Employee')->find($employeeId) : null;
        $orderRepository = $this->get('doctrine_mongodb')->getManager()
            ->getRepository('RestaurantTablesBundle:Order');
        if ($active)
            $orders = $orderRepository->getActiveOrders($employee)->toArray(true);
        else
            $orders = $orderRepository->findAll($employee);
        if (!$orders)
            throw new NotFoundHttpException();
        return array("orders" => $orders);
    }

    /**
     * @param $id
     * @return Order
     * @throws NotFoundHttpException
     * @ApiDoc(
     *   description="View an specific order",
     *   section="Order"
     * )
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
     * @ApiDoc(
     *   description="Delete an order",
     *   section="Order"
     * )
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
     * @ApiDoc(
     *   description="Modify an specific order",
     *   section="Order",
     *   parameters={
     *     {"name"="date", "dataType"="date", "required"=false, "description"="The date of the order"},
     *     {"name"="employee", "dataType"="string", "required"=false, "description"="Employee id"},
     *     {"name"="table", "dataType"="string", "required"=false, "description"="Table id"},
     *     {"name"="active", "dataType"="boolean", "required"=false, "description"="If the order was delivered"}
     *   }
     * )
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
            $active = $request->request->get('active');
            if (!is_null($date))
                $order->setDate($date);
            if (!is_null($orderItems))
                $order->addOrderItem($orderItems);
            if (!is_null($active))
                $order->setActive($active);
            $dm->flush();
            return $order;
        }
        return $form->getErrors();
    }
}