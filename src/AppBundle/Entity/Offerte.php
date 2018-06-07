<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string")
     */
    private $titel;

    /**
     * @ORM\Column(type="string")
     */
    private $deliveryAddress;

    /**
     * @ORM\Column(type="integer")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string")
     */
    private $place;

    /**
     * @ORM\Column(type="date")
     */
    private $delivery_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $korting;

    /**
     * @ORM\Column(type="float")
     */
    private $extra_cost;

    /**
     * @ORM\Column(type="integer")
     */
    private $BTW;

    /**
     * @ORM\OneToMany(targetEntity="Offerte_objects", mappedBy="offerte", cascade={"persist", "remove"}))
     */
    protected $objects;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * @param mixed $titel
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param mixed $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * @param mixed $delivery_date
     */
    public function setDeliveryDate($delivery_date)
    {
        $this->delivery_date = $delivery_date;
    }

    /**
     * @return mixed
     */
    public function getKorting()
    {
        return $this->korting;
    }

    /**
     * @param mixed $korting
     */
    public function setKorting($korting)
    {
        $this->korting = $korting;
    }

    /**
     * @return mixed
     */
    public function getExtraCost()
    {
        return $this->extra_cost;
    }

    /**
     * @param mixed $extra_cost
     */
    public function setExtraCost($extra_cost)
    {
        $this->extra_cost = $extra_cost;
    }

    /**
     * @return mixed
     */
    public function getBTW()
    {
        return $this->BTW;
    }

    /**
     * @param mixed $BTW
     */
    public function setBTW($BTW)
    {
        $this->BTW = $BTW;
    }




    /**
     * Add object
     *
     * @param \AppBundle\Entity\Offerte_objects $object
     *
     * @return Offerte
     */
    public function addObject(\AppBundle\Entity\Offerte_objects $object)
    {
        $this->objects[] = $object;
        $object->setOfferte($this);

        return $this;
    }

    /**
     * Remove object
     *
     * @param \AppBundle\Entity\Offerte_objects $object
     */
    public function removeObject(\AppBundle\Entity\Offerte_objects $object)
    {
        $this->objects->removeElement($object);
//        $object->setOfferte(null);
    }

    /**
     * Get objects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjects()
    {
        return $this->objects;
    }
}
