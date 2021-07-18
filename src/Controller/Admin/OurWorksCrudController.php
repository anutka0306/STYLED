<?php

namespace App\Controller\Admin;

use App\Entity\OurWorks;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Intervention\Image\ImageManager;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichFileType;

class OurWorksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OurWorks::class;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('imageFile')->setUploadDir('public/img/our-works')->setBasePath('/img/our-works/')->setFormTypeOption('multiple', true),

            /*Field::new('id'),*/
            AssociationField::new('priceModel'),
        ];
    }

}
