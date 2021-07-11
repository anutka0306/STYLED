<?php

namespace App\Controller\Admin;

use App\Entity\PriceModel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceModel::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Модель')
            ->setEntityLabelInPlural('Модели')
            ->setPaginatorPageSize(100)
            ->setSearchFields(['name', 'nameRus','code']);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Название'),
            TextField::new('nameRus', 'Название рус.'),
            TextField::new('code', 'Алиас'),
            AssociationField::new('priceBrand', 'Бренд'),
            AssociationField::new('class', 'Нормо-час'),
            NumberField::new('increase', 'Наценка'),
            DateTimeField::new('modify_date', 'Дата изменения')->hideOnIndex(),

        ];
    }

}
