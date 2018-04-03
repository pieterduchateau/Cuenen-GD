<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="offerte")
 * @ORM\Entity
 */
class Offerte
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $customerNr;

    /**
     * @ORM\Column(type="integer")
     */
    private $offerteNr;

    /**
     * @ORM\Column(type="string")
     */
    private $paraName;

    /**
     * @ORM\Column(type="string")
     */
    private $paraValue;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCustomerNr()
    {
        return $this->customerNr;
    }

    /**
     * @param mixed $customerNr
     */
    public function setCustomerNr($customerNr)
    {
        $this->customerNr = $customerNr;
    }

    /**
     * @return mixed
     */
    public function getOfferteNr()
    {
        return $this->offerteNr;
    }

    /**
     * @param mixed $offerteNr
     */
    public function setOfferteNr($offerteNr)
    {
        $this->offerteNr = $offerteNr;
    }

    /**
     * @return mixed
     */
    public function getParaName()
    {
        return $this->paraName;
    }

    /**
     * @param mixed $paraName
     */
    public function setParaName($paraName)
    {
        $this->paraName = $paraName;
    }

    /**
     * @return mixed
     */
    public function getParaValue()
    {
        return $this->paraValue;
    }

    /**
     * @param mixed $paraValue
     */
    public function setParaValue($paraValue)
    {
        $this->paraValue = $paraValue;
    }


}

