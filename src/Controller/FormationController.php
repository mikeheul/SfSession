<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="app_formation")
     * @IsGranted("ROLE_USER")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $formations = $doctrine->getRepository(Formation::class)->findBy([], ['intitule' => 'ASC']);
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    /**
     * @Route("/formation/add", name="add_formation")
     * @Route("/formation/edit/{id}", name="edit_formation", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function add(ManagerRegistry $doctrine, Formation $formation = null, Request $request): Response
    {
        if(!$formation) {
            $formation = new Formation();
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $em = $doctrine->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $formation = $form->getData();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/add.html.twig', [
            'formFormation' => $form->createView(),
            'editMode' => $formation->getId()
        ]);
    }

    /**
     * @Route("/formation/{id}", name="show_formation", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }
}
