<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\CourseComment;
use App\Entity\LessonDoc;
use App\Entity\Notification;
use App\Entity\NotificationTemplate;
use App\Entity\Page;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SectionMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SubMenuItem;
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
        yield MenuItem::section('overview');
        yield MenuItem::linkToUrl('Website', 'fas fa-home', $this->generateUrl('app_index'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');

        yield MenuItem::section('articles');
        yield MenuItem::linkToCrud('Seiten', 'fa fa-file', Page::class);

        yield MenuItem::section('tutorials');
        yield MenuItem::linkToCrud('Kurse', 'fa fa-list', Course::class);
        yield MenuItem::linkToCrud('Dokumente', 'fa fa-file', LessonDoc::class);
        yield MenuItem::linkToCrud('Kommentare', 'fa fa-comment', CourseComment::class);

        yield MenuItem::section('users');
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-user', User::class);

        yield MenuItem::section('notifications');
        yield MenuItem::linkToCrud('templates', 'fa fa-file', NotificationTemplate::class);
        yield MenuItem::linkToCrud('notifications', 'fa fa-file', Notification::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToUrl('My Profile', 'fas fa-user', $this->generateUrl('tutorium_index'))
            ]);
    }

}
