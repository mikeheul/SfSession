<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Entity\FormModule;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="app_session")
     * @IsGranted("ROLE_USER")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $sessions = $doctrine->getRepository(Session::class)->findBy([], ['intitule' => 'ASC']);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    /**
     * @Route("/session/addStagiaire/{idSe}/{idSt}", name="add_stagiaire_session", requirements={"idSe"="\d+", "idSt"="\d+"})
     * @ParamConverter("session", options={"mapping": {"idSe": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idSt": "id"}})
     */
    public function addStagiaire(ManagerRegistry $doctrine, Session $session, Stagiaire $stagiaire) {

        // $session = $doctrine->getRepository(Session::class)->find($request->attributes->get('idSe'));
        // $stagiaire = $doctrine->getRepository(Stagiaire::class)->find($request->attributes->get('idSt'));
        $em = $doctrine->getManager();
        $session->addStagiaire($stagiaire);
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }   

    /**
     * @Route("/session/removeStagiaire/{idSe}/{idSt}", name="remove_stagiaire_session", requirements={"idSe"="\d+", "idSt"="\d+"})
     * @ParamConverter("session", options={"mapping": {"idSe": "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idSt": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function removeStagiaire(ManagerRegistry $doctrine, Session $session, Stagiaire $stagiaire) {

        // $session = $doctrine->getRepository(Session::class)->find($request->attributes->get('idSe'));
        // $stagiaire = $doctrine->getRepository(Stagiaire::class)->find($request->attributes->get('idSt'));
        $em = $doctrine->getManager();
        $session->removeStagiaire($stagiaire);
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }   

    /**
     * @Route("/session/addProgramme/{idSe}/{idMod}", name="add_module_session", requirements={"idSe"="\d+", "idMod"="\d+"})
     * @ParamConverter("session", options={"mapping": {"idSe": "id"}})
     * @ParamConverter("module", options={"mapping": {"idMod": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function addProgramme(ManagerRegistry $doctrine, Request $request, Session $session, FormModule $module) {

        // $session = $doctrine->getRepository(Session::class)->find($request->attributes->get('idSe'));
        // $module = $doctrine->getRepository(FormModule::class)->find($request->attributes->get('idMod'));
        $em = $doctrine->getManager();
        $pr = new Programme();
        $pr->setSession($session);
        $pr->setFormModule($module);

        $nbJours = $request->request->get('nbJours');
        
        $pr->setNbJours($nbJours);
        
        $em->persist($pr);
        $em->flush();
        
        $session->addProgramme($pr);
        
        $em->persist($session);
        $em->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /**
     * @Route("/session/removeProgramme/{idSe}/{idPr}", name="remove_programme_session", requirements={"idSe"="\d+", "idPr"="\d+"})
     * @ParamConverter("session", options={"mapping": {"idSe": "id"}})
     * @ParamConverter("programme", options={"mapping": {"idPr": "id"}})
     * @IsGranted("ROLE_USER")
     */
    public function removeProgramme(ManagerRegistry $doctrine, Request $request, Session $session, Programme $programme) {

        // $session = $doctrine->getRepository(Session::class)->find($request->attributes->get('idSe'));
        // $prog = $doctrine->getRepository(Programme::class)->find($request->attributes->get('idPr'));
        // $session->removeProgramme($prog);
        $session->removeProgramme($programme);
        $em = $doctrine->getManager();
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    /**
     * @Route("/session/add", name="add_session")
     * @Route("/session/edit/{id}", name="edit_session", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function add(ManagerRegistry $doctrine, Session $session = null, Request $request): Response
    {
        if(!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);
        $em = $doctrine->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $session = $form->getData();
            $em->persist($session);
            $em->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/add.html.twig', [
            'formSession' => $form->createView(),
            'editMode' => $session->getId()
        ]);
    }

    /**
     * @Route("/session/{id}", name="show_session", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Session $session, SessionRepository $sr): Response
    {
        $nonInscrits = $sr->findNonInscrits($session->getId());
        $nonProgrammes = $sr->findNonProgrammes($session->getId());
    
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
            'nonProgrammes' => $nonProgrammes
        ]);
    }
}
