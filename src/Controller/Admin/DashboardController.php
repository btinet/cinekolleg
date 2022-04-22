<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\CourseComment;
use App\Entity\LessonDoc;
use App\Entity\Page;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cinekolleg');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Website', 'fas fa-home', $this->generateUrl('app_index'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Seiten', 'fa fa-file', Page::class);
        yield MenuItem::linkToCrud('Kurse', 'fa fa-list', Course::class);
        yield MenuItem::linkToCrud('Kommentare', 'fa fa-comment', CourseComment::class);
        yield MenuItem::linkToCrud('Dokumente', 'fa fa-file', LessonDoc::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToUrl('My Profile', 'fas fa-user', $this->generateUrl('tutorium_index'))
            ]);
    }

}
