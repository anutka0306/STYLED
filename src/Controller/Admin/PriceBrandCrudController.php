<?php

namespace App\Controller\Admin;

use App\Entity\PriceBrand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;


class PriceBrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceBrand::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Марка')
        ->setEntityLabelInPlural('Марки')
        ->setSearchFields(['id','name','nameRus','code'])
        ->setPaginatorPageSize(100);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Название'),
            AssociationField::new('class','Нормо-час'),
            NumberField::new('increase', 'Наценка'),
            TextField::new('nameRus', 'Название рус.'),
            TextField::new('code', 'Алиас'),
            DateTimeField::new('modifyDate', 'Дата изменения')->hideOnIndex(),
        ];
    }

}
