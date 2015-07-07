<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Store;
use Restaurant\CashBundle\Document\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\StoreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class StoreController extends Controller
{

    /**
     * @param int $id
     * @return mixed
     * @ApiDoc(
     *   description="View a Store",
     *   section="Store"
     * )
     * @View()
     */
    function getStoreAction($id)
    {
        $store = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Store')
            ->findOneById($id);
        if(is_null($store))
            throw new NotFoundHttpException();

        return $store;
    }

    /**
     * @return array
     * @ApiDoc(
     *   description="View all Stores",
     *   section="Store"
     * )
     * @View()
     */
    function getStoresAction()
    {
        $stores = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Store')
            ->findAll();

        return array('stores' => $stores);
    }

    /**
     * @param Request $request
     * @return mixed
     * @ApiDoc(
     *   description="Create a Store",
     *   section="Store",
     *   parameters={
     *     {"name"="address", "dataType"="string", "required"=false, "description"="Address"},
     *     {"name"="manager", "dataType"="string", "required"=false, "description"="Manager id"}
     *   }
     * )
     * @View()
     */
    function postStoreAction(Request $request)
    {
        $store = new Store();
        $form = $this->createForm(new StoreType());
        $form->submit($request->request->all());
        if($form->isValid())
        {
            $address = $request->request->get('address');
            $store->setAddress($address);
            $manager = $request->request->get('manager');
            $dm = $this->get('doctrine_mongodb')
                ->getManager();
            if(!is_null($manager)) {
                $employee = $dm->getRepository('RestaurantCashBundle:Employee')->find($manager);
                if(!is_null($employee))
                    $store->setManager($employee);
            }
            $dm->persist($store);
            $dm->flush();
            return $store;
        }
        return $form->getErrors();
    }

    /**
     * @param int $id
     * @return array
     * @ApiDoc(
     *   description="Delete a Store",
     *   section="Store"
     * )
     * @View()
     */
    public function deleteStoreAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $store = $dm->getRepository('RestaurantTablesBundle:Store')
            ->findOneById($id);
        if (is_null($store))
            throw new NotFoundHttpException();
        $dm->remove($store);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     * @ApiDoc(
     *   description="Modify a Store",
     *   section="Store",
     *   parameters={
     *     {"name"="address", "dataType"="string", "required"=false, "description"="Address"},
     *     {"name"="manager", "dataType"="string", "required"=false, "description"="Manager id"}
     *   }
     * )
     * @View()
     */
    public function patchStoreAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $store = $dm->getRepository('RestaurantTablesBundle:Store')->findOneById($id);
        if(is_null($store))
            throw new NotFoundHttpException;
        $form = $this->createForm(new StoreType());
        $form->submit($request->request->all());
        if($form->isValid())
        {
            $address = $request->request->get('address');
            $manager = $request->request->get('manager');
            if ($address)
                $store->setAddress($address);
            if(!is_null($manager)) {
                $employee = $dm->getRepository('RestaurantCashBundle:Employee')->find($manager);
                if(!is_null($employee))
                    $store->setManager($employee);
            }
            $dm->flush();
            return $store;
        }
        return $form->getErrors();
    }
}