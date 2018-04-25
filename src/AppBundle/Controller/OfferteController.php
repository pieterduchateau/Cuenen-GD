<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offerte;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;


class OfferteController extends Controller
{
    /**
     * @Route("/offerte/{offerte_nr}", name="getOfferte")
     */
    public function getOfferte($offerte_nr)
    {
        $html2pdf = new Html2Pdf();
        $offerte_parameters = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findBy(array(
            'offerteNr' => $offerte_nr));

        $parameters = array();
        $parameter_objects = array();
        foreach ($offerte_parameters as $param) {
            if ($param->getParaName() == "productrow") {
                array_push($parameter_objects, $param->getParaValue());
            } else {
                $parameters[$param->getParaName()] = $param->getParaValue();
            }
        }

        $parameters['producten'] = $parameter_objects;

        $customer_Parameters = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte_parameters[0]->getCustomerNr()));

        if($customer_Parameters->getShop() == "CE")
        {
            $html2pdf->writeHTML($this->render('template/offerte_cue.html.twig', array(
                'offerteParameters' => $parameters,
                'customerParameters' => $customer_Parameters
            ))->getContent());
            $html2pdf->output();
        }else{
            $html2pdf->writeHTML($this->render('template/offerte_GM.html.twig', array(
                'offerteParameters' => $parameters,
                'customerParameters' => $customer_Parameters
            ))->getContent());
            $html2pdf->output();
        }
    }

    /**
     * @Route("/laadbon/{laadbon_nr}", name="getlaadbon")
     */
    public function getlaadbon($laadbon_nr)
    {
        $html2pdf = new Html2Pdf();
        $offerte_parameters = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findBy(array(
            'offerteNr' => $laadbon_nr));

        $parameters = array();
        $parameter_objects = array();
        foreach ($offerte_parameters as $param) {
            if ($param->getParaName() == "productrow") {
                array_push($parameter_objects, $param->getParaValue());
            } else {
                $parameters[$param->getParaName()] = $param->getParaValue();
            }
        }

        $parameters['producten'] = $parameter_objects;

        $customer_Parameters = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte_parameters[0]->getCustomerNr()));

        if($customer_Parameters->getShop() == "CE")
        {
            $html2pdf->writeHTML($this->render('template/laadbon_cue.html.twig', array(
                'offerteParameters' => $parameters,
                'customerParameters' => $customer_Parameters
            ))->getContent());
            $html2pdf->output();
        }else{
            $html2pdf->writeHTML($this->render('template/laadbon_GM.html.twig', array(
                'offerteParameters' => $parameters,
                'customerParameters' => $customer_Parameters
            ))->getContent());
            $html2pdf->output();
        }
    }

    /**
     * @Route("/{shop}/editOfferte/{offerteNr}", name="editOfferte")
     */
    public function editOfferte($shop, $offerteNr)
    {

        $offerteParameters = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findBy(array(
            'offerteNr' => $offerteNr));


        if($shop == "CE")
        {
            return $this->render('default/edit_offerte.html.twig', array(
                'shop' => $shop,
                'customerID' => $offerteParameters[0]->getCustomerNr(),
                'parameters' => $offerteParameters
            ));
        }else{
            return $this->render('default/edit_offerte_GM.html.twig', array(
                'shop' => $shop,
                'customerID' => $offerteParameters[0]->getCustomerNr(),
                'parameters' => $offerteParameters
            ));
        }
    }

    /**
     * @Route("/{shop}/addOfferte/{customerId}", name="addOfferte")
     */
    public function addOfferte($shop, $customerId)
    {
        if($shop == "CE")
        {
            return $this->render('default/add_offerte_cue.html.twig', array(
                'shop' => $shop,
                'customerID' => $customerId
            ));
        }else{
            return $this->render('default/add_offerte_GM.html.twig', array(
                'shop' => $shop,
                'customerID' => $customerId
            ));
        }
    }

    /**
     * @Route("/removeOfferte", name="removeOfferte")
     */
    public function removeOfferte(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $offerte_nr = $request->request->get('offerte_id');

        $offerte_parameters = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findBy(array(
            'offerteNr' => 29));

        foreach ($offerte_parameters as $parameter) {
            $em->remove($parameter);
        }

        $em->flush();
        return new JsonResponse();

    }

    /**
     * @Route("/{shop}/GenerateOfferte", name="GenerateOfferte")
     */
    public function GenerateOfferte($shop, Request $request)
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

        if($shop == "CE")
        {
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
        }else if($shop == "GM")
        {
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
}
