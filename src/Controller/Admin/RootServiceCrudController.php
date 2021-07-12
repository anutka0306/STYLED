<?php

namespace App\Controller\Admin;

use App\Entity\RootService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RootServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RootService::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Услуги')
            ->setEntityLabelInSingular('Услуга')
            ->setPaginatorPageSize(100);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id', 'ID')->onlyOnIndex(),
            TextField::new('path', 'Алиас'),
            TextField::new('name','Название'),
            TextField::new('h1', 'H1'),
            AssociationField::new('service', 'Услуга прайса'),
            AssociationField::new('price_category', 'Категория услуги')->hideOnIndex(),
            //Подгружает
            AssociationField::new('parent', 'Родитель')->hideOnIndex(),
            TextField::new('meta_title', 'Title')->hideOnIndex(),
            Field::new('meta_description','Description')->hideOnIndex(),
            CodeEditorField::new('text', 'Текст')->hideOnIndex(),
            BooleanField::new('published', 'Активно'),
            NumberField::new('rating_value', 'Рейтинг')->hideOnIndex(),
            NumberField::new('rating_count', 'Кол-во голосов')->hideOnIndex(),
            DateTimeField::new('modify_date', 'Дата обновления')->hideOnIndex(),
        ];
    }

}
