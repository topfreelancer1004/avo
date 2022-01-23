<?php

namespace App\Controller\Admin;

use App\Entity\Aj;
use App\Entity\Devis;
use App\Entity\Invoice;
use App\Form\AjFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DevisCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Devis::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
       return $crud->setFormOptions(
           ['compound'=>true]
       );
    }

    public function createEntity(string $entityFqcn)
    {
        $devi = new Devis();
        $devi->setUser($this->getUser());
        return $devi;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere("entity.user = :user");
        $qb->setParameter("user", $this->getUser());
        return $qb;
    }

    public  function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('client')->setQueryBuilder( function (QueryBuilder $qb){
                $qb->andWhere("entity.user = :user");
                $qb->setParameter("user", $this->getUser());
            }),
            AssociationField::new('procedur')->setQueryBuilder( function (QueryBuilder $qb){
                $qb->andWhere("entity.user = :user");
                $qb->setParameter("user", $this->getUser());
            }),
            DateField::new("created_at", "Created at"),
            MoneyField::new("amount")->setCurrency('EUR'),
            AssociationField::new('aj')->setQueryBuilder( function (QueryBuilder $qb){
                $qb->andWhere("entity.user = :user");
                $qb->setParameter("user", $this->getUser());
            }),
            BooleanField::new('paid')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $invoiceUrl = $this->adminUrlGenerator
            ->setController(InvoiceCrudController::class)
            ->setAction(Crud::PAGE_NEW);
        $addInvoice = Action::new('addInvoice', 'Invoice')
                            ->linkToUrl($invoiceUrl);
        return $actions->add(Crud::PAGE_INDEX, $addInvoice);
    }


    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
