<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Controller
{
    /**
     * @Route("/config/home", name="config_index")
     */
    public function homepage()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")->findAll();
        $offertes = $em->getRepository("AppBundle:User")->findAll();
        return $this->render('Configuration/index.html.twig', array(
            'users' => $users
        ));

    }

    /**
     * @Route("/backup_db/{file_name}", name="backup_db")
     * @param $file_name
     * @return Response
     */
    public function backup_db($file_name)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository("AppBundle:Config")->findOneBy(array(
            'paraName' => 'last_update'));

        $config->setParaValue($file_name);

        $em->flush();
        return new Response('OK', 200);

    }


}
