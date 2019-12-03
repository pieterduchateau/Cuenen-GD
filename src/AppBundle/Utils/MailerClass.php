<?php
/**
 * Created by PhpStorm.
 * User: ducha
 * Date: 11/12/2018
 * Time: 1:43 PM
 */

namespace AppBundle\Utils;

use \AppBundle\Entity\Offerte;
use \AppBundle\Entity\Customer;
use Spipu\Html2Pdf\Html2Pdf;
use Swift_Attachment;
use Symfony\Component\Config\Definition\Exception\Exception;

class MailerClass
{

    private $offerte;
    private $customer;
    private $renderedView;
    private $filetype;
    private $shop;
    private $body;
    private $mailer;
    private $sendcopycheck;
    private $senderAdresses = array(
        "CE" => "info@cue-events.eu",
        "GM" => "myriam.hex@telenet.Be");

    /**
     * MailerClass constructor.
     */
    public function __construct(Offerte $offerte,Customer $customer, $shop, \Swift_Mailer $mailer, $sendcopycheck)
    {
        $this->offerte = $offerte;
        $this->customer = $customer;
        $this->shop = $shop;
        $this->mailer = $mailer;
        $this->sendcopycheck = $sendcopycheck;
    }

    public function generatePDFTemplate($filetype)
    {
        $this->filetype = $filetype;
        if ($this->shop === "CE") {
            if ($filetype == "offerte") {
                $template = "offerte_cue.html.twig";
            } else {
                $template = "factuur_cue.html.twig";
            }
        } else if ($this->shop === "GM") {
            if ($filetype == "offerte") {
                $template =  "offerte_GM.html.twig";
            } else if ($filetype == "factuur") {
                $template = "factuur_GM.html.twig";
            }
        };

        return $template;
    }

    public function GeneratePDF()
    {
        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
            $html2pdf->writeHTML($this->renderedView);

            $filename = $this->offerte->getCustomerNr() . "_" . $this->offerte->getId() . '.pdf';
            $html2pdf->output(sys_get_temp_dir() . "/" . $filename, 'F');

            $filepath = sys_get_temp_dir() . "/" . $filename;

            if ($this->shop == 'CE')
                $helper = "Cue-Events";
            else {
                $helper = "Gordijnen Myriam";
            }
            $subject = ucfirst($this->filetype) . ' | ' . $helper;



            $message = (new \Swift_Message($subject))
                ->setFrom($this->senderAdresses[$this->shop])
                ->setTo($this->customer->getEmail())
                ->attach(Swift_Attachment::fromPath($filepath)->setFilename($this->filetype . ".pdf"))
                ->addPart($this->body,'text/html');

            if($this->sendcopycheck === "checked"){
                $message->setBcc($this->senderAdresses[$this->shop]);
            }

            $this->mailer->send($message);

            return "1";

        } catch (Exception $e) {
            return "0" . $e;
        }
    }

    /**
     * @param mixed $renderedView
     */
    public function setRenderedView($renderedView)
    {
        $this->renderedView = $renderedView;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }




}