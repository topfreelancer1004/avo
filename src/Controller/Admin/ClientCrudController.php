<?php

namespace App\Controller\Admin;

use App\Entity\Client;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ClientCrudController extends AbstractCrudController
{
    /**
     * @var AdminUrlGenerator
     */
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateFormat(DateTimeField::FORMAT_SHORT)
            ->setSearchFields(["first_name", "last_name", "email"])
            ->setDefaultSort(["created_at" => "DESC"])
            ->setPaginatorPageSize(3)
            ->setPaginatorRangeSize(0);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->onlyOnIndex(),
            TextField::new("first_name", "Name"),
            TextField::new("last_name", "Last Name"),
            TextField::new("email"),
            TextField::new("phone"),
            TextField::new("address"),
            TextField::new("city"),
            TextField::new("zip"),
            DateField::new("created_at")->onlyOnDetail(),
            MoneyField::new('paiments_sum')->setCurrency("EUR")->setLabel('Paid')->onlyOnIndex(),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function createEntity(string $entityFqcn)
    {
        $client = new Client();
        $client->setUser($this->getUser());
        return $client;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $qb = $this->get(EntityRepository::class)
                   ->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $qb->andWhere("entity.user = :user");
        $qb->setParameter("user", $this->getUser());
        return $qb;


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
