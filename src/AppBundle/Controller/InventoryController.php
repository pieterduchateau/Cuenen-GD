<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Items;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class InventoryController extends Controller
{
    /**
     * @Route("/{shop}/inventorylist", name="inventorylist")
     */
    public function inventorylist($shop,Request $request)
    {
        $extensions = array('jpeg','png','jpg');
        $items = new Items();
        $form = $this->createFormBuilder($items)
            ->add('description', TextType::class, array('label' => 'Omschrijving :'))
            ->add('amount', IntegerType::class, array('label' => 'Aantal :'))
            ->add('price', IntegerType::class, array('label' => 'Prijs :'))
            ->add('item', FileType::class, array('label' => 'Foto :'))
            ->add('upload', SubmitType::class, array('label' => 'Toevoegen'))
            ->getForm();
        $form->handleRequest($request);

        //count how many images there are for that offerte for the name.
        $items_helper = $this->getDoctrine()
            ->getRepository("AppBundle:Items")
            ->findByShop($shop);

        $count = count($items_helper);


        if ($form->isSubmitted() && $form->isValid()) {
            $item = $items->getitem();
            $items = $form->getData();
            if ($item->isValid() && in_array($item->guessExtension(),$extensions)) {
                $url = $this->getParameter('image_directory');
                $url = str_replace(" ", "", $url);
                $name = $count . "_itemlist_" . rand(1,100) . "." . $item->guessExtension();
                $items->setImage($this->getParameter('public_image_directory') . "/"  . $name);
                $items->setShop($shop);
                $item->move($url, $name);
                $em = $this->getDoctrine()->getManager();
                $em->persist($items);
                $em->flush();
                return $this->redirectToRoute('inventorylist',array('shop' => $shop));
            }
        }
        return $this->render('default/inventorylist.html.twig', array(
            'shop' => $shop,
            'items' => $items_helper,
            'form' => $form->createView()
        ));
    }



    /**
     * @Route("/{shop}/removeItem", name="removeItem")
     */
    public function removeItem($shop,Request $request)
    {
        try {
            $itemID = $request->request->get('itemID');
            $em = $this->getDoctrine()->getManager();
            $filesystem = new Filesystem();
            $items = $this->getDoctrine()->getRepository("AppBundle:Items")->findBy(array(
                'id' => $itemID));

            foreach ($items as $item) {
                $em->remove($item);
                $filesystem->remove($this->get('kernel')->getRootDir().'/../web'. $item->getImage());
            }

            $em->flush();
            return new JsonResponse("Item succesvol verwijderd.");
        } catch (\Exception $e) {
            return new JsonResponse($e);
        }
    }
}
