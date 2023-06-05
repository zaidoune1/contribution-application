<?php

namespace App\Controller\Admin;

use App\Entity\OperationsBancaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class OperationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OperationsBancaire::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Operations')
            ->setEntityLabelInSingular('Operation')

            ->setPageTitle("index", "Contribution_app - Administration des opérations")

            ->setPaginatorPageSize(10);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('libelle'),
            TextField::new('longitude'),
            TextField::new('latitude'),
                AssociationField::new('user')
                ->hideOnForm(),
                TextField::new('placeName'),
                // ->hideOnIndex(),
                // DateTime::new('updatedAt')
                // ->hideOnForm()
        ];
    }
}
