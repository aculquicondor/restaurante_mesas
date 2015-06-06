<?php


namespace Restaurant\TablesBundle\Controller;

use Doctrine\Common\Collections\Criteria;
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
        return $form->getErrors();
    }

    /**
     * @param $orderId
     * @param $itemId
     * @return OrderItem
     * @throws NotFoundHttpException
     * @View()
     */
    public function getItemAction($orderId, $itemId)
    {
        $docOrder = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Order')
            ->findOneById($orderId);

        if (is_null($docOrder))
            throw new NotFoundHttpException();

        $orderItems = $docOrder->getOrderitems();
        foreach($orderItems as $item)
        {
            if($item->getId() == $itemId)
            {
                return $item;
            }
        }
    }

    /**
     * @param $orderId
     * @return OrderItems
     * @throws NotFoundHttpException
     * @View()
     */
    public function getItemsAction($orderId)
    {
        $docOrder = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Order')
            ->findOneById($orderId);
        if (is_null($docOrder))
            throw new NotFoundHttpException();
        return $docOrder->getOrderItems();
    }

    /**
     * @param $orderId
     * @param $itemId
     * @return array()
     * @View()
     * @throws NotFoundHttpException
     */
    public function deleteItemAction($orderId, $itemId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $docOrder = $dm->getRepository('RestaurantTablesBundle:Order')
            ->findOneById($orderId);
        if (is_null($docOrder))
            throw new NotFoundHttpException();
        $docOrder->removeOrderItem($itemId);
        $dm->persist($docOrder);
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
    public function patchItemAction(Request $request, $orderId, $itemId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $orderItem = $dm->getRespository('RestaurantTablesBundle:OrderItem')->findOneById($itemId);
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