<?php


namespace Restaurant\TablesBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Restaurant\TablesBundle\Document\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\OrderItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class OrderItemController extends Controller
{
    /**
     * @param $orderId
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|OrderItem
     * @ApiDoc(
     *   description="Create an item in the order",
     *   section="Order",
     *   parameters={
     *     {"name"="menu_item", "dataType"="string", "required"=false, "description"="item id"},
     *     {"name"="observations", "dataType"="string", "required"=false, "description"="observations about the item of the menu in the order"}
     *   }
     * )
     * @View()
     **/
    public function postItemAction($orderId, Request $request)
    {
        $orderItem = new OrderItem();
        $form = $this->createForm(new OrderItemType());
        $form ->submit($request->request->all());
        if($form->isValid()){
            $dm = $this->get('doctrine_mongodb')->getManager();
            $menuItem = $request->request->get('menu_item');
            $observations = $request->request->get('observations');
            if (!is_null($menuItem))
            {
                $docMenuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')
                    ->find($menuItem);
                if (!is_null($docMenuItem))
                    $orderItem->setMenuItem($docMenuItem);
            }
            $orderItem->setDelivered(false);
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
     * @ApiDoc(
     *   description="View an specific item of the menu in the order",
     *   section="Order"
     * )
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
        throw new NotFoundHttpException();
    }

    /**
     * @param $orderId
     * @return array
     * @throws NotFoundHttpException
     * @ApiDoc(
     *   description="View the items of the menu in the order",
     *   section="Order"
     * )
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
        return array('items' => $docOrder->getOrderItems());
    }

    /**
     * @param $orderId
     * @param $itemId
     * @return array()
     * @ApiDoc(
     *   description="Delete an item of the menu in the order",
     *   section="Order"
     * )
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
        foreach($docOrder->getOrderItems() as $item)
        {
            if($item->getId() == $itemId)
                $docOrder->removeOrderItem($item);
        }
        $dm->persist($docOrder);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $orderId
     * @param $itemId
     * @return \Symfony\Component\Form\FormErrorIterator|OrderItem
     * @ApiDoc(
     *   description="Modify the items in the order",
     *   section="Order",
     *   parameters={
     *     {"name"="menu_item", "dataType"="string", "required"=false, "description"="item id"},
     *     {"name"="observation", "dataType"="string", "required"=false, "description"="observations about the item of the menu in the order"}
     *   }
     * )
     * @View()
     * @throws NotFoundHttpException
     */
    public function patchItemAction(Request $request, $orderId, $itemId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $docOrder = $dm->getRepository('RestaurantTablesBundle:Order')->findOneById($orderId);
        if(!$docOrder)
            throw new NotFoundHttpException();
        $form = $this->createForm(new OrderItemType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $orderItems = $docOrder->getOrderitems();
            $menuItem = $request->request->get('menu_item');
            $delivered = $request->request->get('delivered');
            $observations = $request->request->get('observations');
            foreach($orderItems as $item)
            {
                if($item->getId() == $itemId)
                {
                    if (!is_null($menuItem)) {
                        $docMenuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')
                            ->findOneById($menuItem);
                        $item->setMenuItem($docMenuItem);
                    }
                    if (!is_null($delivered)) {
                        $item->setDelivered($delivered);
                    }
                    if (!is_null($observations)) {
                        $item->setObservations($observations);
                    }
                    $dm->flush();
                    return $item;
                }
            }
        }
        return $form->getErrors();
    }
}
