<?php

namespace App\Controller;


use App\Entity\cours;
use App\Form\AjoutCoursType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourController extends AbstractController
{

    /**
     * @Route("/cours", name="cours")
     */
    public function index()
    {
        return $this->render('listCour/index.html.twig', [
            'controller_name' => 'CourController',
        ]);
    }

    /**
     * @Route("/cours/add", name="add_cour")
     */
    public function new(Request $request)
    {
        $article = new cours();
        $form = $this->createForm(AjoutCoursType::class,$article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            $image = $form->get('image')->getData();
//            if($image){
//                $imageOrigineFilename = pathinfo($image->getclientOrigineName(),PATHINFO_FILENAME);
//                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $imageOrigineFilename);
//                $newFilname = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
//                try {
//                    $image->move(
//                        $this->getParameter('cour_directory'),
//                        $newFilname
//                    );
//
//                }catch (FileException $fe){
//                    var_dump($fe);
//                    exit();
//                }
//
//
//            }
            $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('homepage');

        }
        return $this->render('listCour/index.html.twig', [
            'form' => $form->createView(),
        ]);


    }
}
