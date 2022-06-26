<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @IsGranted("ROLE_USER")
     */
    public function index(SessionRepository $sr, Request $request): Response
    {
        $pastSessions = $sr->findPastSessions();
        $progressSessions = $sr->findProgressSessions();
        $futureSessions = $sr->findFutureSessions();

        return $this->render('home/index.html.twig', [
            'pastSessions' => $pastSessions,
            'progressSessions' => $progressSessions,
            'futureSessions' => $futureSessions,
        ]);
    }
}
