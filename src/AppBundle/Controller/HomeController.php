<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offerte;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends Controller
{
    /**
     * @Route("/{shop}/home", name="homepage")
     */
    public function homepage($shop)
    {
        return $this->render('default/index.html.twig', array(
            'shop' => $shop
        ));
    }

    /**
     * @Route("/select_shop", name="select_shop")
     */
    public function select_shop()
    {
        return $this->render('default/select_shop.html.twig');
    }

    /**
     * @Route("/", name="redirect_select_shop")
     */
    public function redirect_select_shop()
    {
        return $this->redirectToRoute('select_shop');
    }

    /**
     * @Route("/{shop}/calendar", name="calendar")
     */
    public function calendar($shop)
    {
        return $this->render('default/calendar.html.twig', array(
            'shop' => $shop
        ));
    }

    /**
     * @Route("/getEvents", name="getEvents")
     */
    public function getEvents(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $first = $request->request->get('start');
        $last = $request->request->get('end');
        $shop = $request->request->get('shop');

        $repository = $this->getDoctrine()
            ->getRepository(Offerte::class);
        $query = $repository->createQueryBuilder('o')
            ->where('o.delivery_date >= :firstday')
            ->andwhere('o.delivery_date <= :endday')
            ->setParameter('firstday', $first)
            ->setParameter('endday', $last)
            ->getQuery();
        $events = $query->getResult();

        $eventArray = [];
        foreach ($events as $event) {
            $h1 = array(
                'id' => $event->getId(),
                'title' => $event->getTitel(),
                'start' => $event->getDeliveryDate()->format('Y-m-d'),
                'shop' => $shop
            );

            array_push($eventArray, $h1);
        }

        return new JsonResponse($eventArray);
    }
}
