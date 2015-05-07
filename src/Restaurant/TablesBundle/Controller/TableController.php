<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;

class TableController extends Controller
{
    /**
     * @param $capacity
     * @return Table
     * @View()
     */
    public function newTableAction($capacity)
    {
        $table = new Table();
        $table->setAvailable(true);
        $table->setCapacity($capacity);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($table);
        $dm->flush();
        return $table;
    }

    /**
     * @param $id
     * @return mixed
     * @View()
     */
    public function getTableAction($id)
    {
        return $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Table')
            ->findOneById($id);
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
    public function getAvailableTablesAction()
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
     */
    public function removeTableAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $table = $dm->getRepository('RestaurantTablesBundle:Table')->findOneById($id);
        $dm->remove($table);
        $dm->flush();
        return array();
    }

    /**
     * @param $available
     * @param $id
     * @return mixed
     * @View()
     */
    public function updateTableAvailableAction($available, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $table = $dm->getRepository('RestaurantTablesBundle:Table')->findOneById($id);
        $table->setAvailable($available);
        $dm->flush();
        return $table;
    }

}