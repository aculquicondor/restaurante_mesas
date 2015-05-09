<?php

namespace Restaurant\TablesBundle\Controller;

use Restaurant\TablesBundle\Document\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Restaurant\TablesBundle\Form\Type\ReservationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ReservationController extends Controller
{
    public function postReservationAction(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createForm(new ReservationType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $clientId = $request->request->get('client');
            $tableId = $request->request->get('table');
            $estimatedArrivalTime = $request->request->get('estimatedArrivalTime', 'now');
            $dm = $this->get('doctrine_mongodb')->getManager();
            if (!is_null($tableId)) {
                $table = $dm->getRepository('RestaurantTablesBundle:Table')
                    ->findOneById($tableId);
                if(!$table)
                    throw new NotFoundHttpException("Table not Found");
                $reservation->addTable($table);
            }
            if (!is_null($clientId)) {
                $client = $dm->getRepository('Restaurant\CashBundle\Document\Client')
                    ->findOneById($clientId);
                if (!$client)
                    throw new NotFoundHttpException("Client not Found");
                $reservation->setClient($client);
            }
            if (!is_null($estimatedArrivalTime)) {
                if (\DateTime::createFromFormat('Y-m-d H:i', $estimatedArrivalTime) === false)
                    throw new NotFoundHttpException("Invalid date format");
                $reservation->setEstimatedArrivalTime(new \DateTime($estimatedArrivalTime));
            }
            $dm->flush();
            return $reservation;
        }
        return $form->getErrors();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getReservationAction($id)
    {
        $reservation = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Reservation')
            ->findOneById($id);
        if (!$reservation)
            throw new NotFoundHttpException();
        return $reservation;
    }

    /**
     * @return array
     */
    public function getReservationsAction()
    {
        $reservations = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('RestaurantTablesBundle:Reservation')
            ->findAll();
        return array('reservations' => $reservations);
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteReservationAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $reservation = $dm->getRepository('RestaurantTablesBundle:Repository')->findOneById($id);
        if (!$reservation)
            throw new NotFoundHttpException();
        $dm->remove($reservation);
        $dm->flush();
        return array();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\FormErrorIterator
     */
    public function patchReservationAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $reservation = $dm->getRepository('RestaurantTablesBundle:Reservation')->findOneById($id);
        if (!$reservation)
            throw new NotFoundHttpException();
        $form = $this->createForm(new ReservationType());
        $form->submit($request->request->all());
        if($form->isValid()){
            $clientId = $request->request->get('client');
            $tableId = $request->request->get('table');
            $estimatedArrivalTime = $request->request->get('estimatedArrivalTime');
            if (!is_null($tableId)) {
                $table = $dm->getRepository('RestaurantTablesBundle:Table')
                    ->findOneById($tableId);
                if(!$table)
                    throw new NotFoundHttpException("Table not Found");
                $reservation->addTable($table);
            }
            if (!is_null($clientId)) {
                $client = $dm->getRepository('Restaurant\CashBundle\Document\Client')
                    ->findOneById($clientId);
                if(!$client)
                    throw new NotFoundHttpException("Client not Found");
                $reservation->setClient($client);
            }
            if (!is_null($estimatedArrivalTime)) {
                $reservation->setEstimatedArrivalTime($estimatedArrivalTime);
            }
            $dm->flush();
            return $reservation;
        }
        return $form->getErrors();
    }
}
