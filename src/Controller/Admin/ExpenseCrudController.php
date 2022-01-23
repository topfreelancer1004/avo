<?php

namespace App\Controller\Admin;

use App\Entity\Expense;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class ExpenseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Expense::class;
    }
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto,$entityDto, $fields,$filters);
        $qb->andWhere("entity.user = :user")->setParameter("user", $this->getUser());
        return $qb;
    }

    public function createEntity(string $entityFqcn)
    {
        $Expense = new Expense();
        $Expense->setUser($this->getUser());
        return $Expense;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('charge_type')->setLabel('Expense type')->setQueryBuilder( function (QueryBuilder $qb){
                $qb->andWhere("entity.user = :user");
                $qb->setParameter("user", $this->getUser());
            }),
            MoneyField::new('val')->setCurrency('EUR'),
            DateField::new('created_at')
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
