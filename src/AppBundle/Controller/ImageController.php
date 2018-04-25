<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Files;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ImageController extends Controller
{
    /**
     * @Route("/{shop}/images/{offerte_id}", name="images")
     */
    public function images($shop,$offerte_id)
    {
        $images = $this->getDoctrine()
            ->getRepository("AppBundle:Files")
            ->findby(array('offerte_id' => $offerte_id));

        return $this->render('default/images.html.twig',array(
            'shop' => $shop,
            'offerte_id' => $offerte_id,
            'images' => $images
        ));

    }

    /**
     * @Route("/removeimage", name="removeimage")
     */
    public function removeimage(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fname = $request->request->get('fname');

        $offerte_parameters = $this->getDoctrine()->getRepository("AppBundle:Files")->findBy(array(
            'fname' => $fname));

        foreach ($offerte_parameters as $parameter) {
            $em->remove($parameter);
        }

        $em->flush();
        $filesystem = new Filesystem();
        $filesystem->remove($this->get('kernel')->getRootDir().'/../web/uploads/images/'. $fname);
        return new JsonResponse();

    }

    /**
     * @Route("/{shop}/images/addimages/{offerte_id}", name="addimages")
     */
    public function addimages($shop,$offerte_id,Request $request)
    {

        $extensions = array('jpeg','png','jpg','pdf');
        $files = new Files();
        $form = $this->createFormBuilder($files)
            ->add('file', FileType::class, array('label' => 'File'))
            ->add('upload', SubmitType::class, array('label' => 'Upload File'))
            ->getForm();
        $form->handleRequest($request);

        //count how many images there are for that offerte for the name.
        $images = $this->getDoctrine()
            ->getRepository("AppBundle:Files")
            ->findby(array('offerte_id' => $offerte_id));

        $count = count($images);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $files->getFile();
            $files = $form->getData();
            $files->setFname($file->getClientOriginalName());
            $files->setFsize($file->getClientSize());
            $files->setOfferteId($offerte_id);
            if ($file->isValid() && in_array($file->guessExtension(),$extensions)) {
                $url = $this->getParameter('image_directory');
                $url = str_replace(" ", "", $url);
                $name = $offerte_id . "_" . $count . "_" . $file->getClientOriginalName();

                $files->setFextension($file->guessExtension());
                $files->setFname($name);
                $files->setFsize($file->getClientSize());
                $file->move($url, $name);
                $public_url = $this->getParameter('public_image_directory') . "/" . $name;
                $files->setFurl(str_replace(" ", "", $public_url));
                $em = $this->getDoctrine()->getManager();
                $em->persist($files);
                $em->flush();
                return $this->redirectToRoute('images',array('shop' => $shop,'offerte_id' => $offerte_id));
            }
        }
        return $this->render('default/add_image.html.twig', array(
            'shop' => $shop,
            'form' => $form->createView()
        ));

    }
}
