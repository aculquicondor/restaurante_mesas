<?php
namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Restaurant\TablesBundle\Form\Type\MenuItemType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * @Prefix("menu")
 */
class MenuItemController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|MenuItem
     * @ApiDoc(
     *   description="Create an item of the menu",
     *   section="MenuItem",
     *   parameters={
     *     {"name"="name", "dataType"="string", "required"=false, "description"="The name of the item (dish) of the menu"},
     *     {"name"="price", "dataType"="integer", "required"=false, "description"="The cost of the menu's dish"},
     *     {"name"="available", "dataType"="boolean", "required"=false, "description"="If the item (dish) is available in the menu"}
     *   }
     * )
     * @View()
     */
    public function postItemAction(Request $request)
    {
        $menuItem = new MenuItem();
        $form = $this->createForm(new MenuItemType());
        $form->submit($request->request->all());
        if($form->isValid())
        {
            $name = $request->request->get('name');
            $price= $request->request->get('price');
            $menuItem->setName($name);
            $menuItem->setAvailable(true);
            $menuItem->setPrice($price);
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($menuItem);
            $dm->flush();
            return $menuItem;
        }
        return $form->getErrors();
    }

    /**
     * @param $id
     * @return MenuItem
     * @throws NotFoundHttpException
     * @ApiDoc(
     *   description="View an specific item (dish) of the menu",
     *   section="MenuItem"
     * )
     * @View()
     */
    public function getItemAction($id)
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
     * @ApiDoc(
     *   description="View all the items",
     *   section="MenuItem",
     *   filters={
     *     {"name"="available", "dataType"="boolean"}
     *   }
     * )
     * @View()
     */
    public function getItemsAction(Request $request)
    {
        $available = $request->get('available');
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

    /**
     * @param $id
     * @return array
     * @ApiDoc(
     *   description="Delete an item (dish) of the menu",
     *   section="MenuItem",
     * )
     * @View()
     * @throws NotFoundHttpException
     */
    public function deleteItemsAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $menuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')->findOneById($id);
        if(is_null($menuItem))
            throw new NotFoundHttpException();
        $dm->remove($menuItem);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|MenuItem
     * @ApiDoc(
     *   description="Modify an item (dish) of the menu",
     *   section="MenuItem",
     *   parameters={
     *     {"name"="name", "dataType"="string", "required"=false, "description"="The name of the item (dish)"},
     *     {"name"="price", "dataType"="integer", "required"=false, "description"="The cost of the item (dish)"},
     *     {"name"="available", "dataType"="boolean", "required"=false, "description"="If the item (dish) is available in the menu"}
     *   }
     * )
     * @View()
     * @throws NotFoundHttpException
     */
    public function patchItemAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $menuItem = $dm->getRepository('RestaurantTablesBundle:MenuItem')->findOneById($id);
        if (is_null($menuItem))
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