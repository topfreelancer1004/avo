<?php

namespace App\Controller\Admin;

use App\Entity\Aj;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;

class AjCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Aj::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $aj = new Aj();
        $aj->setUser($this->getUser());
        return $aj;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("name", "Aj"),
            MoneyField::new("val", "Summ")->setCurrency('EUR'),
        ];
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

}
