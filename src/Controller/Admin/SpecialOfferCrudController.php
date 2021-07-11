<?php

namespace App\Controller\Admin;

use App\Entity\SpecialOffer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpecialOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpecialOffer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Акции')
            ->setEntityLabelInSingular('Акция')
            ->setPaginatorPageSize(20);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Название'),
            TextField::new('description', 'Описание')->hideOnIndex(),
            NumberField::new('oldPrice', 'Старая цена'),
            NumberField::new('newPrice', 'Новая цена'),
            BooleanField::new('published', 'Активность'),
            NumberField::new('ordering','Позиция'),
            TextField::new('metaTitle','Title')->hideOnIndex(),
            TextField::new('metaDescription', 'Description')->hideOnIndex(),
            TextField::new('slug', 'Алиас'),
            DateTimeField::new('modifyDate', 'Дата изменения')->hideOnIndex()
        ];
    }

}
