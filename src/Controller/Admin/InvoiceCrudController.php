<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class InvoiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invoice::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $invoice = new Invoice();
        $invoice->setUser($this->getUser());
        return $invoice;
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

    public function configureFields(string $pageName): iterable
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
            MoneyField::new('amount')->setCurrency('EUR'),
        ];
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
