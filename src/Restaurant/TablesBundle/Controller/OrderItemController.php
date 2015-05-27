<?php


namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\OrderItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderItemController extends Controller
{
    /**
     * @param $orderId
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|OrderItem
     * @View()
     */
    public function postItemAction($orderId, Request $request)
    {
        $orderItem = new OrderItem();
        $form = $this->createForm(new OrderItemType());
        $form ->submit($request->request->all());
        if($form->isValid()){
            $dm = $this->get('doctrine_mongodb')->getManager();
            $menuItem = $request->request->get('menuItem');
            $observations = $request->request->get('observations');
            if (!is_null($menuItem))
            {
                $docMenuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')
                    ->find($menuItem);
                if (!is_null($docMenuItem))
                    $orderItem->setMenuItem($docMenuItem);
            }
            $orderItem->setObservations($observations);
            $docOrder = $dm->getRepository('RestaurantTablesBundle:Order')
                ->find($orderId);
            $docOrder->addOrderItem($orderItem);
            $dm->persist($orderItem);
            $dm->persist($docOrder);
            $dm->flush();
            return $orderItem;
        }
    }

    /**
     * @param $orderId
     * @param $id
     * @return OrderItem
     * @throws NotFoundHttpException
     * @View()
     */
    public function getItemAction($orderId, $id)
    {
        $orderItem = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:OrderItem')
            ->findOneById($id);
        if (is_null($orderItem))
            throw new NotFoundHttpException();
        return $orderItem;
    }

    /**
     * @param $orderId
     * @param $id
     * @return array()
     * @View()
     * @throws NotFoundHttpException
     */
    public function deleteItemAction($orderId, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $orderItem = $dm->getRepository('RestaurantTablesBundle:OrderItem')->findOneById($id);
        if (is_null($orderItem))
            throw new NotFoundHttpException();
        $dm->remove($orderItem);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $orderId
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|OrderItem
     * @View()
     * @throws NotFoundHttpException
     */
    public function patchItemAction(Request $request, $orderId, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $orderItem = $dm->getRespository('RestaurantTablesBundle:OrderItem')->findOneById($id);
        if(!$orderItem)
            throw new NotFoundHttpException();
        $form = $this->createForm(new OrderItemType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $menuItem = $request->request->get('menuItem');
            $observations = $request->request->get('observations');
            if(!is_null($menuItem)){
                $orderItem->setMenuItem($menuItem);
            }
            if(!is_null($observations)){
                $orderItem->setObservations($observations);
            }
            $dm->flush();
            return $orderItem;
        }
        return $form->getErrors();
    }
}