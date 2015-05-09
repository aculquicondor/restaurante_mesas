<?php
namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\MenuItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MenuItemController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|MenuItem
     * @View()
     */
    public function postMenuItemAction(Request $request)
    {
        $menuItem = new MenuItem();
        $form = $this->createForm(new MenuItemType());
        $form->submit($request->request->all());
        //if($form->isValid())
        //{
            $name = $request->request->get('name');
            $price= $request->request->get('price');
            $menuItem->setName($name);
            $menuItem->setAvailable(true);
            $menuItem->setPrice($price);
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($menuItem);
            $dm->flush();
            return $menuItem;
        //}
        //return $form->getErrors();
    }

    /**
     * @param $id
     * @return MenuItem
     * @throws NotFoundHttpException
     * @View()
     */
    public function getMenuItemAction($id)
    {
        $menuItem = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:MenuItem')
            ->findOneById($id);
        if(!$menuItem)
            throw new NotFoundHttpException();
        return $menuItem;
    }

    /**
     * @param Request $request
     * @return array
     * @View()
     */
    public function getMenuItemAction(Request $request)
    {
        $available = $request->get('avaiable');
        if($available)
        {
            $menuItems = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('RestaurantTablesBundle:MenuItem')
                ->getAvailableMenuItems();
        } else {
            $menuItems = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('RestaurantTablesBundle:MenuItem')
                ->findAll();
        }
        return array('menuItems' => $menuItems);
    }

    /*
     * @param $id
     * @return array
     * @View()
     * @throws NotFoundHttpException
     */
    public function deleteMenuItemAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $menuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')->findOneById($id);
        if(!$menuItem)
            throw new NotFoundHttpException();
        $dm->remove($menuItem);
        $dm->flush();
        return array();
    }

    /*
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|MenuItem
     * @View()
     * @throws NotFoundHttpException
     */
    public function patchMenuItem(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $menuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')->findOneById($id);
        if (!$menuItem)
            throw new NotFoundHttpException();
        $form = $this->createForm(new MenuItemType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $name = $request->request->get('name');
            $available = $request->request->get('available');
            $price = $request->request->get('price');
            if(!is_null($name)){
                $menuItem->setName($name);
            }
            if(!is_null($available)){
                $menuItem->setAvailable($available);
            }
            if(!is_null($price)){
                $menuItem->setPrice($price);
            }
            $dm->flush();
            return $menuItem;
        }
        return $form->getErrors();
    }


}