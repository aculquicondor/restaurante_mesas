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
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|OrderItem
     * @View()
     */
    public function postOrderItemAction(Request $request)
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
                $docMenuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')->find($menuItem);
                if (!is_null($docMenuItem))
                    $orderItem->setMenuItem($docMenuItem);
            }
            $orderItem->setObservations($observations);
            $dm->persist($orderItem);
            $dm->flush();
            return $orderItem;
        }
    }

    /**
     * @param Request $request
     * @return OrderItem
     * @throws NotFoundHttpException
     * @View()
     */
    public function getOrderItemAction($id)
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
     * @param $id
     * @return array()
     * @view()
     * @throws NotFoundHttpException
     */
    public function deleteOrderItemAction($id)
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
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|OrderItem
     * @View()
     * @throws NotFoundHttpException
     */
    public function patchOrderItemAction(Request $request, $id)
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