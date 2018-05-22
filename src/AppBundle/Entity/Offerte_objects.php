<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="Offerte_objects")
 * @ORM\Entity
 */
class Offerte_objects
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
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $omschrijving;

    /**
     * @ORM\Column(type="integer")
     */
    private $aantal;

    /**
     * @ORM\Column(type="decimal")
     */
    private $prijs;

    /**
     * @ORM\Column(type="decimal")
     */
    private $totaal;

    /**
     * @ORM\ManyToOne(targetEntity="Offerte")
     * @ORM\JoinColumn(name="offerte_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $offerte;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getOmschrijving()
    {
        return $this->omschrijving;
    }

    /**
     * @param mixed $omschrijving
     */
    public function setOmschrijving($omschrijving)
    {
        $this->omschrijving = $omschrijving;
    }

    /**
     * @return mixed
     */
    public function getAantal()
    {
        return $this->aantal;
    }

    /**
     * @param mixed $aantal
     */
    public function setAantal($aantal)
    {
        $this->aantal = $aantal;
    }

    /**
     * @return mixed
     */
    public function getPrijs()
    {
        return $this->prijs;
    }

    /**
     * @param mixed $prijs
     */
    public function setPrijs($prijs)
    {
        $this->prijs = $prijs;
    }

    /**
     * @return mixed
     */
    public function getTotaal()
    {
        return $this->totaal;
    }

    /**
     * @param mixed $totaal
     */
    public function setTotaal($totaal)
    {
        $this->totaal = $totaal;
    }

    /**
     * Set offerte
     *
     * @param \AppBundle\Entity\Offerte $offerte
     *
     * @return Offerte_objects
     */
    public function setOfferte(\AppBundle\Entity\Offerte $offerte = null)
    {
        $this->offerte = $offerte;

        return $this;
    }

    /**
     * Get offerte
     *
     * @return \AppBundle\Entity\Offerte
     */
    public function getOfferte()
    {
        return $this->offerte;
    }
}
