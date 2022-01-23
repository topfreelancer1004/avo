<?php

namespace App\Controller\Admin;

use App\Entity\Aj;
use App\Entity\Charge;
use App\Entity\Client;
use App\Entity\Devis;
use App\Entity\Expense;
use App\Entity\Paiment;
use App\Entity\Procedure;
use App\Entity\Tax;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $admin_url_generator){
        $this->adminUrlGenerator = $admin_url_generator;
    }

    /**
     * @Route("/adm", name="admin")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $data = [];
        $clientRepository = $this->getDoctrine()->getRepository(Client::class);
        $data['userCnt']= $clientRepository->getClientsForCurrentMonth($user);
        $data['averageClients'] = $clientRepository->getAverageClients($user);

        $paymentRepository = $this->getDoctrine()->getRepository(Paiment::class);
        $data['totalEarnings'] = $paymentRepository->getTotalEarnings($user);
        $data['totalPerMonth'] = $paymentRepository->getTotalPerMonth($user);
        $total_clients = $clientRepository->getClientsCount($user);
        $total_clients = $total_clients==0?1:$total_clients;
        $data["averagePerClient"] = $data['totalEarnings']/$total_clients;

        $payment = $paymentRepository->getBestProcedure($user);

        if($payment !== null) {
            $data['bestProcedure'] = $payment->getProcedur();
            $data['procedureUrl'] = $this->adminUrlGenerator->setController(ProcedureCrudController::class)
                                                  ->setAction(Action::DETAIL)
                                                  ->setEntityId($payment->getProcedur()->getId())
                                                  ->generateUrl();
        }
        else {
            $data['bestProcedure'] = ["name"=>"No data"];
            $data['procedureUrl'] = "";
        }

        return $this->render("dashboard/index.html.twig", $data);

        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Avo');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard("Dashboard", "fa fa-home");
        yield MenuItem::linkToRoute("Report", "fa fa-list", "admin_report_general");
        yield MenuItem::linkToCrud("Devis", "fa fa-list", Devis::class);
        yield MenuItem::linkToCrud('Paiments', 'fa fa-credit-card', Paiment::class);
        yield MenuItem::linkToCrud('Expense', 'fa fa-list', Expense::class);
        yield MenuItem::linkToCrud("Clients", "fa fa-users", Client::class);
        yield MenuItem::subMenu("Parameters", "fa fa-tools")->setSubItems([
            MenuItem::linkToCrud("Materies", "fa fa-cogs", Procedure::class),
            MenuItem::linkToCrud("Charges", "fa fa-money-bill-alt", Charge::class),
            MenuItem::linkToCrud("Taxes", "fa fa-search-dollar", Tax::class),
            MenuItem::linkToCrud("Aj", "fa fa-euro-sign", Aj::class),
        ]);
        yield MenuItem::linkToCrud("Users", "fa fa-users-cog", User::class)->setPermission('ROLE_ADMIN');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
