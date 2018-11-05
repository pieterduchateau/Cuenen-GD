<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spipu\Html2Pdf\Html2Pdf;


class MailController extends Controller
{
    /**
     * @Route("/{shop}/mails", name="mails")
     */
    public function mails($shop)
    {
        
    }

    /**
     * @Route("/sendmail", name="sendmail")
     */
    public function sendmail(\Swift_Mailer $mailer, Request $request)
    {
        $body = $request->request->get('body');
        $filetype = $request->request->get('file');
        $offerteId = $request->request->get('offerteID');
        $shop = $request->request->get('shop');

        $offerte = $this->getDoctrine()->getRepository("AppBundle:Offerte")->findOneBy(array(
            'id' => $offerteId));
        $customer = $this->getDoctrine()->getRepository("AppBundle:Customer")->findOneBy(array(
            'id' => $offerte->getCustomerNr()));

        if($shop === "CE"){
            if ($filetype == "offerte"){
                $template = "offerte_cue.html.twig";
            }else{
                $template = "factuur_cue.html.twig";
            }
        }else if ($shop === "GM"){
            if ($filetype == "offerte"){
                $template = "offerte_GM.html.twig";
            }else if ($filetype == "factuur"){
                $template = "factuur_GM.html.twig";
        }};

        $htmldata = $this->renderView('template/' . $template ,array(
            'offerte' => $offerte,
            'customer' => $customer
        ));

        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
            $html2pdf->writeHTML($htmldata);

            $filename = $offerte->getCustomerNr() . "_" . $offerte->getId() . '.pdf';
            $html2pdf->output(sys_get_temp_dir() . "/" . $filename, 'F');
            $filepath = sys_get_temp_dir() . "/" . $filename;

            if($shop == 'CE')
                $helper = "Cue-Events";
            else{
                $helper = "Gordijnen Myriam";
            }
            $subject = ucfirst($filetype) . '|' . $helper;

            $message = (new \Swift_Message($subject))
                ->setTo('duchateaupieter@hotmail.com')
                ->attach(Swift_Attachment::fromPath($filepath)->setFilename($filetype . ".pdf"))
                ->setBody(
                    $this->renderView("mailtemplate" . "/" . $template, array(
                            'body' => $body,
                            'customer' => $customer
                        )
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $responseMessage = ucfirst($filetype) . ' succesvol verstuurd naar ' . $customer->getEmail();
            return new Response($responseMessage);
        } catch (Html2PdfException $e) {
            return new Response($e);
        }
    }
}
