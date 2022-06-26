<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="app_categorie")
     * @IsGranted("ROLE_USER")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findBy([], ['intitule' => 'ASC']);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categorie/edit/{id}", name="edit_categorie", requirements={"id"="\d+"})
     * @Route("/categorie/add", name="add_categorie")
     */
    public function edit(ManagerRegistry $mr, Categorie $categorie = null, Request $request): Response
    {
        if(!$categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $categorie = $form->getData();
            $mr->getManager()->persist($categorie);
            $mr->getManager()->flush();

            return $this->redirectToRoute('show_categorie', ["id" => $categorie->getId()]);
        }

        return $this->render('categorie/edit.html.twig', [
            'formCategorie' => $form->createView(),
            'editMode' => $categorie->getId(),
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="show_categorie", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
