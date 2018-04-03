<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ConfigurationController extends Controller
{
    /**
     * @Route("/config/home", name="config_index")
     */
    public function homepage()
    {
        $users = $customer = $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->findAll();
        return $this->render('Configuration/index.html.twig',array(
            'users' => $users
        ));

    }
}
