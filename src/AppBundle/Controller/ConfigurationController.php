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
        $config_db_update = $config = $em->getRepository("AppBundle:Config")->findOneBy(array(
            'paraName' => 'last_update'));
        $h1 =  str_replace('.sql','',$config_db_update->getParaValue());
        $h2 = explode('-',$h1);
        $h2[0] = str_replace('_','/',$h2[0]);
        $h2[1] = str_replace('_',':',$h2[1]);

        $date = $h2[0] .'-' . $h2[1];
        return $this->render('Configuration/index.html.twig', array(
            'users' => $users,
            'backup_date' => $date
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
