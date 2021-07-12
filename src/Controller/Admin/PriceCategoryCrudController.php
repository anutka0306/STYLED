<?php

namespace App\Controller\Admin;

use App\Entity\PriceCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Категрии')
            ->setEntityLabelInSingular('Категория')
            ->setPaginatorPageSize(100)
            ->setSearchFields(['name']);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id','ID')->onlyOnIndex(),
            TextField::new('name', 'Название'),
            /*NumberField::new('parent', 'Родитель'),*/
            TextField::new('slug', 'Алиас')
        ];
    }

}
