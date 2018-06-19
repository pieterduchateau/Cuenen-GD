<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offerte;
use AppBundle\Entity\Offerte_objects;
use AppBundle\Form\offerte_CUE_form;
use AppBundle\Form\offerte_GM_form;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;


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
        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerte_nr));

        $html2pdf = new Html2Pdf('P', 'A4', 'en');
        $html2pdf->pdf->SetTitle($offerte->getTitel());

        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));

        if ($shop == "CE") {
            $template = "template/offerte_cue.html.twig";
        } else {
            $template = "template/offerte_GM.html.twig";
        }

        $html2pdf->writeHTML($this->render($template, array(
            'offerte' => $offerte,
            'customer' => $customer
        ))->getContent());
        $html2pdf->output();
    }

    /**
     * @Route("{shop}/laadbon/{laadbon_nr}", name="getlaadbon")
     */
    public
    function getlaadbon($shop, $laadbon_nr)
    {
        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $laadbon_nr));

        $html2pdf = new Html2Pdf();
        $html2pdf->pdf->SetTitle($offerte->getTitel());

        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));

        if ($shop == "CE") {
            $template = "template/laadbon_cue.html.twig";
        } else {
            $template = "template/laadbon_GM.html.twig";
        }

        $html2pdf->writeHTML($this->render($template, array(
            'offerte' => $offerte,
            'customer' => $customer
        ))->getContent());
        $html2pdf->output();
    }

    /**
     * @Route("{shop}/factuur/{offerteId}", name="getfactuur")
     */
    public
    function getfactuur($shop, $offerteId)
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
        }
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

        if($shop == "CE")
        {
            $form = $this->createForm(offerte_CUE_form::class, $offerte);
        }else{
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
        if($shop == "CE")
        {
            $form = $this->createForm(offerte_CUE_form::class, $offerte);
        }else{
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

    /**
     * @Route("/{shop}/GenerateOfferte", name="GenerateOfferte")
     */
    public
    function GenerateOfferte($shop, Request $request)
    {

        $products = $request->get('parameters');
        $deliveryAddress = $request->get('leveradres');
        $titel = $request->get('titel');
        $postcode = $request->get('postcode');
        $deliveryDate = $request->get('leverdatum');
        $customerID = $request->get('customerID');
        $korting = $request->get('korting');

        if ($request->request->has('offerteID')) {
            $offertenr = $request->get('offerteID');

            //delete old offerte
            $em = $this->getDoctrine()->getManager();
            $offerteparams = $em->getRepository("AppBundle:Offerte")->findBy(array(
                'offerteNr' => $offertenr
            ));

            foreach ($offerteparams as $param) {
                $em->remove($param);
                $em->flush();
            }
        } else {
            $offerteConfig = $this->getDoctrine()
                ->getRepository("AppBundle:Config")
                ->findOneBy(array(
                    'paraName' => 'offertenr'
                ));

            $offertenr = $offerteConfig->getParaValue();

            //set new offertenr
            $offerteConfig->setParaValue($offertenr + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offerteConfig);
        }


        //set parameters for offerte
        $offerte1 = new Offerte();
        $offerte1->setCustomerNr($customerID);
        $offerte1->setOfferteNr($offertenr);
        $offerte1->setParaName('deliveryAddress');
        $offerte1->setParaValue($deliveryAddress);
        $em->persist($offerte1);

        $offerte2 = new Offerte();
        $offerte2->setCustomerNr($customerID);
        $offerte2->setOfferteNr($offertenr);
        $offerte2->setParaName('postcode');
        $offerte2->setParaValue($postcode);
        $em->persist($offerte2);

        $offerte3 = new Offerte();
        $offerte3->setCustomerNr($customerID);
        $offerte3->setOfferteNr($offertenr);
        $offerte3->setParaName('deliveryDate');
        $offerte3->setParaValue($deliveryDate);
        $em->persist($offerte3);

        $offerte4 = new Offerte();
        $offerte4->setCustomerNr($customerID);
        $offerte4->setOfferteNr($offertenr);
        $offerte4->setParaName('korting');
        $offerte4->setParaValue($korting);
        $em->persist($offerte4);

        $offerte5 = new Offerte();
        $offerte5->setCustomerNr($customerID);
        $offerte5->setOfferteNr($offertenr);
        $offerte5->setParaName('titel');
        $offerte5->setParaValue($titel);
        $em->persist($offerte5);

        if ($shop == "CE") {
            //put parameters inside single string
            for ($i = 0; $i < sizeof($products['code']); $i++) {
                $row = $products['code'][$i] . ",-," . $products['omschrijving'][$i] . ",-," . $products['aantal'][$i] . ",-," . $products['prijs'][$i];

                $offerte6 = new Offerte();
                $offerte6->setCustomerNr($customerID);
                $offerte6->setOfferteNr($offertenr);
                $offerte6->setParaName('productrow');
                $offerte6->setParaValue($row);
                $em->persist($offerte6);

            }
        } else if ($shop == "GM") {
            $offerte6 = new Offerte();
            $offerte6->setCustomerNr($customerID);
            $offerte6->setOfferteNr($offertenr);
            $offerte6->setParaName('productrow');
            $offerte6->setParaValue($products);
            $em->persist($offerte6);

            $offerte7 = new Offerte();
            $offerte7->setCustomerNr($customerID);
            $offerte7->setOfferteNr($offertenr);
            $offerte7->setParaName('subtotaal');
            $offerte7->setParaValue($request->get('subtotaal'));
            $em->persist($offerte7);
        }

        $em->flush();


        return $this->redirectToRoute('show_customer', array(
            'shop' => $shop,
            'customer_id' => $customerID));

    }

    /**
     * @Route("test", name="test")
     */
    public function test()
    {
        return $this->render('template/offerte_GM.html.twig');

    }

}
