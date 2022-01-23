<?php

namespace App\Controller\Admin;

use App\Entity\Charge;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class ChargeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Charge::class;
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
        $charge = new Charge();
        $charge->setUser($this->getUser());
        return $charge;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_NEW, Action::INDEX);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name", "Charge Name")->setColumns(3),
            IntegerField::new("val", "VAT tx")->setColumns(3)
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
