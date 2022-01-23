<?php

namespace App\Controller\Admin;

use App\Entity\Paiment;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class PaimentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Paiment::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $payment = new Paiment();
        $payment->setUser($this->getUser());
        return $payment;
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
            AssociationField::new('devi')->setQueryBuilder( function (QueryBuilder $qb){
                $qb->andWhere("entity.user = :user");
                $qb->setParameter("user", $this->getUser());
            })->setLabel('Devi date'),
            AssociationField::new('aj')->setQueryBuilder( function (QueryBuilder $qb){
                $qb->andWhere("entity.user = :user");
                $qb->setParameter("user", $this->getUser());
            }),
            MoneyField::new('amount')->setCurrency('EUR'),
            MoneyField::new('rest_to_pay')->setCurrency('EUR')->setLabel('Rest')->onlyOnIndex(),
        ];
    }

}
