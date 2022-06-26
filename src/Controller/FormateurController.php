<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    /**
     * @Route("/formateur", name="app_formateur")
     * @IsGranted("ROLE_USER")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $formateurs = $doctrine->getRepository(Formateur::class)->findBy([], ['nom' => 'ASC']);
        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
        ]);
    }

    /**
     * @Route("/formateur/add", name="add_formateur")
     * @Route("/formateur/edit/{id}", name="edit_formateur", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function add(ManagerRegistry $doctrine, Formateur $formateur = null, Request $request): Response
    {
        if(!$formateur) {
            $formateur = new Formateur();
        }

        $form = $this->createForm(FormateurType::class, $formateur);
        $form->handleRequest($request);
        $em = $doctrine->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $formateur = $form->getData();
            $em->persist($formateur);
            $em->flush();

            return $this->redirectToRoute('app_formateur');
        }

        return $this->render('formateur/add.html.twig', [
            'formFormateur' => $form->createView(),
            'editMode' => $formateur->getId()
        ]);
    }

    /**
     * @Route("/formateur/{id}", name="show_formateur", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Formateur $formateur): Response
    {
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur,
        ]);
    }
}
