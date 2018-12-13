<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Offerte;
use AppBundle\Form\offerte_CUE_form;
use AppBundle\Form\offerte_GM_form;
use Doctrine\Common\Collections\ArrayCollection;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Spipu\Html2Pdf\Html2Pdf;


class OfferteController extends Controller
{
    /**
     * @Route("{shop}/offerte/{offerte_id}", name="offerte")
     */
    public function offerte($offerte_id, $shop)
    {
        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerte_id));

        return $this->render('default/offerte.html.twig', array(
            'offerte' => $offerte,
            'shop' => $shop
        ));

    }

    /**
     * @Route("{shop}/offerteToPdf/{offerte_nr}", name="offerteToPdf")
     */
    public function offerteToPdf($shop, $offerte_nr)
    {
        set_time_limit(0);
        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerte_nr));
        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetTitle($offerte->getTitel());

        if ($shop == "CE") {
            $template = "template/offerte_cue.html.twig";
        } else {
            $template = "template/mail.html.twig";
        }

        $htmldata = $this->renderView($template, array(
            'offerte' => $offerte,
            'customer' => $customer
        ));
        $html2pdf->writeHTML($htmldata);

        try {
            $html2pdf->output($offerte->getTitel() . '.pdf');
        } catch (Html2PdfException $e) {

        }
    }

    /**
     * @Route("{shop}/laadbon/{laadbon_nr}", name="getlaadbon")
     */
    public
    function getlaadbon($shop, $laadbon_nr)
    {
        set_time_limit(0);
        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $laadbon_nr));
        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetTitle($offerte->getTitel());

        if ($shop == "CE") {
            $template = "template/laadbon_cue.html.twig";
        } else {
            $template = "template/laadbon_GM.html.twig";
        }

        $htmldata = $this->renderView($template, array(
            'offerte' => $offerte,
            'customer' => $customer
        ));

        $html2pdf->writeHTML($htmldata);

        try {
            $html2pdf->output($offerte->getTitel() . '.pdf');
        } catch (Html2PdfException $e) {

        }
    }

    /**
     * @Route("{shop}/factuur/{offerteId}", name="getfactuur")
     */
    public function getfactuur($shop, $offerteId)
    {

        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerteId));

        $html2pdf = new Html2Pdf();
        $html2pdf->pdf->SetTitle($offerte->getTitel());

        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));

        if ($shop == "GM") {
            $html2pdf->writeHTML($this->render("template/factuur_GM.html.twig", array(
                'offerte' => $offerte,
                'customer' => $customer
            ))->getContent());
            $html2pdf->output();
        } else {
            $html2pdf->writeHTML($this->render("template/factuur_cue.html.twig", array(
                'offerte' => $offerte,
                'customer' => $customer
            ))->getContent());
            $html2pdf->output();
        }
    }

    /**
     * @Route("/factuurwithobject", name="factuurwithobject")
     */
    public function getfactuurwithobject(Request $request)
    {
        $shop = $request->request->get('shop');
        $offerte_nr = $request->get('offerte_nr_3');
        $bodyData = $request->get('factuurbody');

        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerte_nr));

        $html2pdf = new Html2Pdf();
        $html2pdf->pdf->SetTitle($offerte->getTitel());

        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));


        $html2pdf->writeHTML($this->render("template/factuur_cue.html.twig", array(
            'offerte' => $offerte,
            'customer' => $customer,
            'body' => $bodyData
        ))->getContent());


        $html2pdf->output();

    }

    /**
     * @Route("/{shop}/editOfferte/{offerteNr}", name="editOfferte")
     */
    public
    function editOfferte($shop, $offerteNr, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array('id' => $offerteNr));

        $originalObjects = new ArrayCollection();

        foreach ($offerte->getObjects() as $obj) {
            $originalObjects->add($obj);
        }

        if ($shop == "CE") {
            $form = $this->createForm(offerte_CUE_form::class, $offerte);
        } else {
            $form = $this->createForm(offerte_GM_form::class, $offerte);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalObjects as $obj) {
                if (false === $offerte->getObjects()->contains($obj)) {
                    $em->remove($obj);
                }
            }
            $em->persist($offerte);
            $em->flush();
            return $this->redirectToRoute('offerte', array('shop' => $shop, 'offerte_id' => $offerteNr));
        }

        return $this->render('forms/edit_offerte.html.twig', array(
            'form' => $form->createView(),
            'shop' => $shop,
            'customer_id' => $offerte->getCustomerNr()
        ));
    }

    /**
     * @Route("/{shop}/addOfferte/{customerId}", name="addOfferte")
     * @param Request $request
     * @param $shop
     * @param $customerId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public
    function addOfferte(Request $request, $shop, $customerId)
    {

        $em = $this->getDoctrine()->getManager();
        $offerte = new Offerte();
        $offerte->setCustomerNr($customerId);
        if ($shop == "CE") {
            $form = $this->createForm(offerte_CUE_form::class, $offerte);
        } else {
            $form = $this->createForm(offerte_GM_form::class, $offerte);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offerte);
            $em->flush();
            return $this->redirectToRoute('show_customer', array('shop' => $shop, 'customer_id' => $customerId));


        }

        return $this->render('forms/add_offerte.html.twig', array(
            'form' => $form->createView(),
            'shop' => $shop
        ));
    }

    /**
     * @Route("/removeOfferte", name="removeOfferte")
     */
    public
    function removeOfferte(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $offerte_id = $request->request->get('offerte_id');

        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerte_id));

        $em->remove($offerte);

        $em->flush();
        return new JsonResponse();

    }
}
