<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Offerte;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spipu\Html2Pdf\Html2Pdf;
use \AppBundle\Utils\MailerClass;


class MailController extends Controller
{
    /**
     * @Route("/sendmail", name="sendmail")
     */
    public function sendmail(Request $request)
    {
        $body = $request->request->get('body');
        $filetype = $request->request->get('file');
        $offerteId = $request->request->get('offerteID');
        $shop = $request->request->get('shop');
        $omschrijving = $request->request->get('omschrijving');
        $sendcopycheck = $request->request->get('sendcopycheck');

        $offerte = $this->getDoctrine()->getRepository(Offerte::class)->findOneBy(array(
            'id' => $offerteId));
        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(array(
            'id' => $offerte->getCustomerNr()));


        if ($shop === 'GM') {
            $mailerobject = $this->get('swiftmailer.mailer.mailer');

            $Mail = new MailerClass($offerte, $customer, $shop, $mailerobject, $sendcopycheck);
            $Mail->setBody($this->renderView("mailtemplate/mail_GM.html.twig", array('body' => $body)));
        }
        else if ($shop === 'CE') {
            $mailerobject = $this->get('swiftmailer.mailer.mailer2');
            $Mail = new MailerClass($offerte,$customer,$shop,$mailerobject, $sendcopycheck);
            $Mail->setBody($this->renderView("mailtemplate/mail_cue.html.twig", array(
                'body' => $body
            )));
        }

        $template = $Mail->generatePDFTemplate($filetype);

        $htmldata = $this->renderView('template/' . $template, array(
            'offerte' => $offerte,
            'customer' => $customer,
            'body' => $omschrijving
        ));

        $Mail->setRenderedView($htmldata);

        return new Response($Mail->GeneratePDF());

    }
}
