<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\TableType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class TableController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormErrorIterator|Table
     * @View()
     */
    public function postTableAction(Request $request)
    {
        $table = new Table();
        $form = $this->createForm(new TableType(), $table);
        $form->submit($request->request->all());
        if($form->isValid()){
            $capacity = $request->request->get('capacity');
            $table->setCapacity($capacity);
            $table->setAvailable(true);
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($table);
            $dm->flush();
            return $table;
        }
        return $form->getErrors();
    }

    /**
     * @param $id
     * @return Table
     * @throws NotFoundHttpException
     * @View()
     */
    public function getTableAction($id)
    {
        $table = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Table')
            ->findOneById($id);
        if (!$table)
            throw new NotFoundHttpException();
        return $table;
    }

    /**
     * @return array
     * @View()
     */
    public function getTablesAction()
    {
        $tables = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Table')
            ->findAll();
        return array('tables' => $tables);
    }

    /**
     * @return array
     * @View()
     */
    public function getTablesAvailableAction()
    {
        $availableTables = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Table')
            ->getAvailableTables(new \DateTime());
        return array('tables' => $availableTables);
    }

    /**
     * @param $id
     * @return array
     * @View()
     * @throws NotFoundHttpException
     */
    public function deleteTableAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $table = $dm->getRepository('RestaurantTablesBundle:Table')->findOneById($id);
        if (!$table)
            throw new NotFoundHttpException();
        $dm->remove($table);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator|Table
     * @View()
     * @throws NotFoundHttpException
     */
    public function patchTableAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $table = $dm->getRepository('RestaurantTablesBundle:Table')->findOneById($id);
        if (!$table)
            throw new NotFoundHttpException();
        $form = $this->createForm(new TableType(), $table);
        $form->submit($request->request->all());
        if($form->isValid()){
            $capacity = $request->request->get('capacity');
            $available = $request->request->get('available');
            $occupationTime = $request->request->get('occupationTime');
            if($occupationTime){
                $table->setOccupationTime($occupationTime);
            }
            if($available){
                $table->setAvailable($available);
            }
            if($capacity){
                $table->setCapacity($capacity);
            }
            $dm->flush();
            return $table;
        }
        return $form->getErrors();
    }
}
