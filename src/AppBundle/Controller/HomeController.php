<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{
    /**
     * @Route("/{shop}/home", name="homepage")
     */
    public function homepage($shop)
    {
        return $this->render('default/index.html.twig',array(
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
}
