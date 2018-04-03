<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CustomerController extends Controller
{
    /**
     * @Route("/{shop}/customers", name="all_customers")
     */
    public function all_customers($shop)
    {

        $customers = $this->getDoctrine()
            ->getRepository("AppBundle:Customer")
            ->findByshop($shop);
        return $this->render('default/all_customers.html.twig',array(
            'shop' => $shop,
            'customers' => $customers
        ));
    }

    /**
     * @Route("/{shop}/add_customer", name="add_customer")
     */
    public function add_customer($shop, Request $request)
    {
        $customer = new Customer();
        $form = $this->createFormBuilder($customer)
            ->add('first_name', TextType::class,array('label' => 'Voornaam :'))
            ->add('last_name', TextType::class,array('label' => 'Achternaam :'))
            ->add('address', TextType::class,array('label' => 'Adres :'))
            ->add('postcode', NumberType::class,array('label' => 'Postcode :'))
            ->add('date_init', DateType::class,array(
                'label' => 'Datum aanwezig :',
                'widget' => 'single_text'))
            ->add('email', EmailType::class,array('label' => 'Email :'))
            ->add('phone', TextType::class,array('label' => 'Telefoon/GSM :'))
            ->add('btwnumber', TextType::class,array(
                'label' => 'BTW-nummer :',
                'required' => false))
            ->add('info', TextareaType::class,array(
                'label' => 'Extra info :',
                'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Klant toevoegen'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setShop($shop);
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('all_customers',array('shop' => $shop));
        }

        return $this->render('default/add_customer.html.twig',array(
            'shop' => $shop,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{shop}/customer/{customer_id}", name="show_customer")
     */
    public function show_customer($shop,$customer_id)
    {
        $customer = $this->getDoctrine()
            ->getRepository("AppBundle:Customer")
            ->find($customer_id);

        $em = $this->getDoctrine()->getManager();

        $offertesAddressquery = $em->createQueryBuilder()
            ->select('offerte')
            ->from('AppBundle:Offerte','offerte')
            ->where('offerte.customerNr = :nr')
            ->andWhere('offerte.paraName = :address')
            ->setParameter('nr', $customer_id)
            ->setParameter('address', "deliveryAddress")
            ->distinct()
            ->getQuery();
        $offertesAddress = $offertesAddressquery->getResult();

        $offertesDateQuery= $em->createQueryBuilder()
            ->select('offerte')
            ->from('AppBundle:Offerte','offerte')
            ->where('offerte.customerNr = :nr')
            ->andWhere('offerte.paraName = :date')
            ->setParameter('nr', $customer_id)
            ->setParameter('date', "deliveryDate")
            ->distinct()
            ->getQuery();
        $offertesDates = $offertesDateQuery->getResult();

        $offertesTitleQuery= $em->createQueryBuilder()
            ->select('offerte')
            ->from('AppBundle:Offerte','offerte')
            ->where('offerte.customerNr = :nr')
            ->andWhere('offerte.paraName = :titel')
            ->setParameter('nr', $customer_id)
            ->setParameter('titel', "titel")
            ->distinct()
            ->getQuery();
        $offerteTitels = $offertesTitleQuery->getResult();

        return $this->render('default/show_customer.html.twig',array(
            'shop' => $shop,
            'customer' => $customer,
            'offertesAddresses' => $offertesAddress,
            'offertesDates' => $offertesDates,
            'offerteTitels' => $offerteTitels
        ));
    }

    /**
     * @Route("/{shop}/editcustomer/{customerid}", name="editCustomer")
     */
    public function editCustomer($shop,$customerid,Request $request)
    {
        $customer = $this->getDoctrine()
            ->getRepository("AppBundle:Customer")
            ->find($customerid);

        $form = $this->createFormBuilder($customer)
            ->add('first_name', TextType::class,array('label' => 'Voornaam :'))
            ->add('last_name', TextType::class,array('label' => 'Achternaam :'))
            ->add('address', TextType::class,array('label' => 'Adres :'))
            ->add('postcode', NumberType::class,array('label' => 'Postcode :'))
            ->add('date_init', DateType::class,array(
                'label' => 'Datum aanwezig :',
                'widget' => 'single_text'))
            ->add('email', EmailType::class,array('label' => 'Email :'))
            ->add('phone', TextType::class,array('label' => 'Telefoon/GSM :'))
            ->add('btwnumber', TextType::class,array(
                'label' => 'BTW-nummer :',
                'required' => false))
            ->add('info', TextareaType::class,array(
                'label' => 'Extra info :',
                'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Klant aanpassen'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setShop($shop);
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('show_customer',array('shop' => $shop,'customer_id' => $customerid));
        }

        return $this->render('default/edit_customer.html.twig',array(
            'shop' => $shop,
            'form' => $form->createView()
        ));
    }
}
