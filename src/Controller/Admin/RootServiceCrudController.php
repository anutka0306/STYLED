<?php

namespace App\Controller\Admin;

use App\Entity\RootService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            ImageField::new('text_down_bg', 'Фоновое изображение нижнего блока текста')->setUploadDir('/public/images/page-images')->setBasePath('/images/page-images/')->hideOnIndex(),
            CodeEditorField::new('text_down', 'Текст нижний')->hideOnIndex(),
            ImageField::new('text_down_img', 'Картинка нижнего блока')->setUploadDir('/public/images/page-images')->setBasePath('/images/page-images/')->hideOnIndex(),
            CodeEditorField::new('text_down2', 'Текст нижний 2-ой блок')->hideOnIndex(),
            ImageField::new('text_down_img2', 'Картинка нижнего блока2')->setUploadDir('/public/images/page-images')->setBasePath('/images/page-images/')->hideOnIndex(),
            BooleanField::new('published', 'Активно'),
            NumberField::new('rating_value', 'Рейтинг')->hideOnIndex(),
            NumberField::new('rating_count', 'Кол-во голосов')->hideOnIndex(),
            DateTimeField::new('modify_date', 'Дата обновления')->hideOnIndex(),
        ];
    }

}
