<?php

namespace AppBundle\Entity;

// src/AppBundle/Entity/User.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="customer")
 * @ORM\Entity
 */
class Customer
{
    /**
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="firstname",type="string", length=64)
     */
    private $first_name;

    /**
     * @ORM\Column(name="lastname",type="string", length=64)
     */
    private $last_name;

    /**
     * @ORM\Column(name="address",type="string", length=60)
     */
    private $address;

    /**
     * @ORM\Column(name="postcode",type="integer", length=60)
     */
    private $postcode;

    /**
     * @ORM\Column(name="email",type="string", length=60)
     */
    private $email;

    /**
     * @ORM\Column(name="phone",type="string", length=60)
     */
    private $phone;

    /**
     * @ORM\Column(name="btwnumber",type="string", length=60, nullable=true)
     */
    private $btwnumber;

    /**
     * @ORM\Column(name="info",type="string", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(name="shop",type="string")
     */
    private $shop;

    /**
     * @ORM\Column(name="place",type="string")
     */
    private $place;

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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getBtwnumber()
    {
        return $this->btwnumber;
    }

    /**
     * @param mixed $btwnumber
     */
    public function setBtwnumber($btwnumber)
    {
        $this->btwnumber = $btwnumber;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
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




}

