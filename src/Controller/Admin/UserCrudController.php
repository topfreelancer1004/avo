<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->onlyOnDetail(),
            TextField::new('first_name'),
            TextField::new('last_name'),
            EmailField::new("email"),
            TextField::new("password")->onlyWhenUpdating()->setFormType(PasswordType::class),
            DateField::new('created_at'),
            BooleanField::new('status'),
            BooleanField::new('isVerified')->setCustomOptions(['disabled'=>true])->hideWhenUpdating(),
        ];
    }

}
