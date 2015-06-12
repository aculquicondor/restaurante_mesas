<?php

namespace Restaurant\TablesBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Restaurant\TablesBundle\Document\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\TableType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class TableController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @ApiDoc(
     *   description="Create a Table",
     *   section="Table",
     *   parameters={
     *     {"name"="number", "dataType"="integer", "required"=false, "description"="Number"},
     *     {"name"="capacity", "dataType"="integer", "required"=false, "description"="Capacity"}
     *   }
     * )
     * @View()
     */
    public function postTableAction(Request $request)
    {
        $table = new Table();
        $form = $this->createForm(new TableType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $number = $request->request->get('number');
            if (!is_null($number))
                $table->setNumber($number);
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
     * @param string $id
     * @return Table
     * @ApiDoc(
     *   description="View a Table",
     *   section="Table"
     * )
     * @View()
     */
    public function getTableAction($id)
    {
        $table = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Table')
            ->find($id);
        if (!$table)
            throw new NotFoundHttpException();
        return $table;
    }

    /**
     * @param Request $request
     * @param string $id
     * @return array
     * @ApiDoc(
     *   description="View Reservations for a Table",
     *   section="Table",
     *   parameters={
     *     {"name"="time", "dataType"="date", "required"=false, "description"="Custom time"}
     *   }
     * )
     * @View()
     */
    public function getTableReservationsAction(Request $request, $id)
    {
        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine_mongodb')->getManager();
        $table = $dm->getRepository('RestaurantTablesBundle:Table')
            ->find($id);
        if (!$table)
            throw new NotFoundHttpException();
        $time = $request->get('time', 'now');
        $cursor = $dm->getRepository('RestaurantTablesBundle:Reservation')
            ->getReservationsForTableNowOn($table, new \DateTime($time));
        $reservations = array();
        foreach ($cursor as $reservation)
            $reservations[] = $reservation;
        return array('reservations' => $reservations);
    }

    /**
     * @param Request $request
     * @return array
     * @ApiDoc(
     *   description="View all Tables",
     *   section="Table",
     *   filters={
     *     {"name"="available", "dataType"="boolean"}
     *   },
     *   parameters={
     *     {"name"="time", "dataType"="date", "required"=false, "description"="Custom time"}
     *   }
     * )
     * @View()
     */
    public function getTablesAction(Request $request)
    {
        $available = $request->get('available');
        $time = $request->get('time', 'now');
        if ($available) {
            $tables = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('RestaurantTablesBundle:Table')
                ->getAvailableTables(new \DateTime($time));
        } else {
            $tables = $this->get('doctrine_mongodb')
                ->getManager()
                ->getRepository('RestaurantTablesBundle:Table')
                ->findAll();
        }
        return array('tables' => $tables);
    }

    /**
     * @param int $id
     * @return array
     * @ApiDoc(
     *   description="Delete a Table",
     *   section="Table"
     * )
     * @View()
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
     * @param string $id
     * @return mixed
     * @ApiDoc(
     *   description="Modify a Table",
     *   section="Table",
     *   parameters={
     *     {"name"="number", "dataType"="integer", "required"=false, "description"="Number"},
     *     {"name"="capacity", "dataType"="integer", "required"=false, "description"="Capacity"},
     *     {"name"="available", "dataType"="boolean", "required"=false, "description"="Availability"},
     *     {"name"="occupation_time", "dataType"="date", "required"=false, "description"="Occupation time"}
     *   }
     * )
     * @View()
     */
    public function patchTableAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $table = $dm->getRepository('RestaurantTablesBundle:Table')->findOneById($id);
        if (!$table)
            throw new NotFoundHttpException();
        $form = $this->createForm(new TableType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $number = $request->request->get('number');
            $capacity = $request->request->get('capacity');
            $available = $request->request->get('available');
            $occupationTime = $request->request->get('occupation_time');
            if (!is_null($number)) {
                $table->setNumber($number);
            }
            if (!is_null($occupationTime)) {
                $table->setOccupationTime($occupationTime);
            }
            if (!is_null($available)) {
                $table->setAvailable($available);
            }
            if (!is_null($capacity)) {
                $table->setCapacity($capacity);
            }
            $dm->flush();
            return $table;
        }
        return $form->getErrors();
    }
}
