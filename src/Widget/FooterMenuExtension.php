<?php


namespace App\Widget;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\PriceCategoryRepository;


class FooterMenuExtension extends AbstractExtension
{
    /**
     * @var PriceCategoryRepository
     */
    protected $price_category_repository;

    public function __construct(PriceCategoryRepository $price_category_repository){
        $this->price_category_repository = $price_category_repository;
    }

    public function getFunctions():array
    {
        return [
            new TwigFunction('footer_menu', [$this, 'footer_menu'], ['needs_environment'=> true, 'is_safe' => ['html']]),
        ];
    }

    public function footer_menu(Environment $twig):string{
        $items = $this->price_category_repository->findAll();
        return $twig->render('v2/widget/footer_menu.html.twig', compact('items'));
    }

}