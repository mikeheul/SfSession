<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SfSession');
    }

    public function configureMenuItems(): iterable
    {
        return [
                MenuItem::linkToCrud('Formations', 'fa fa-tags', Formation::class),
                MenuItem::linkToCrud('Sessions', 'fa fa fa-calendar', Session::class),
                MenuItem::linkToCrud('Stagiaires', 'fa fa-users', Stagiaire::class),
                MenuItem::linkToCrud('Programmes', 'fa fa-file-text', Programme::class),
                MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class),
        ];
    }
}
