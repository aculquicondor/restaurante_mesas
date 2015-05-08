<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Store;
use Restaurant\CashBundle\Document\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\StoreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreController extends Controller
{

    /**
     * @param $id
     * @return mixed
     * @View()
     */
    function getStoreAction($id)
    {
        return $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Store')
            ->findOneById($id);
    }

    /**
     * @return array
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
     * @return \Symfony\Component\Form\FormErrorIterator|Store
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
            $dm->persist($store);
            $dm->flush();
            return $store;
        }
        return $form->getErrors();
    }

    /**
     * @param $id
     * @return array
     * @View()
     * @throws NotFoundHttpException
     */
    public function deleteStoreAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $store = $dm->getRepository('RestaurantTablesBundle:Store')
            ->findOneById($id);
        if (!$store)
            throw NotFoundHttpException();
        $dm->remove($store);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|Store
     * @View()
     * @throws NotFoundHttpException
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
            if ($address)
                $store->setAddress($address);

            $dm->flush();
        }
        return $form->getErrors();
    }
}