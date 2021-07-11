<?php

namespace App\Service;

use App\Entity\Brand;
use App\Entity\Content;
use App\Entity\Model;
use App\Entity\PriceBrand;
use App\Entity\PriceModel;
use App\Entity\PriceService;
use App\Entity\Service;
use App\Model\PageGeneratorModel;
use App\Repository\BrandRepository;
use App\Repository\ContentRepository;
use App\Repository\ModelRepository;
use App\Repository\PriceBrandRepository;
use App\Repository\PriceModelRepository;
use App\Repository\PriceServiceRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpProgrammist\FileSqlLoggerBundle\FileSqlLogger;
use Psr\Log\LoggerInterface;

class PageGeneratorService
{
    /**
     * @var BrandRepository
     */
    protected $brand_repository;
    /**
     * @var ModelRepository
     */
    protected $model_repository;
    /**
     * @var ServiceRepository
     */
    protected $service_repository;
    /**
     * @var ContentRepository
     */
    protected $content_repository;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PriceServiceRepository
     */
    protected $price_service_repository;
    /**
     * @var TranslateService
     */
    protected $translate_service;
    /**
     * @var PriceBrandRepository
     */
    protected $price_brand_repository;
    /**
     * @var PriceModelRepository
     */
    protected $price_model_repository;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var array
     */
    private $seo;
    
    public function __construct(
        BrandRepository $brand_repository,
        ModelRepository $model_repository,
        ContentRepository $content_repository,
        ServiceRepository $service_repository,
        PriceServiceRepository $price_service_repository,
        PriceBrandRepository $price_brand_repository,
        PriceModelRepository $price_model_repository,
        ConfigService $config_service,
        TranslateService $translate_service,
        EntityManagerInterface $em,
        LoggerInterface $logger,
        FileSqlLogger $sql_logger
    ) {
        $this->brand_repository         = $brand_repository;
        $this->model_repository         = $model_repository;
        $this->service_repository       = $service_repository;
        $this->seo                      = $config_service->getGroup('seo');
        $this->content_repository       = $content_repository;
        $this->em                       = $em;
        $this->price_service_repository = $price_service_repository;
        $this->translate_service        = $translate_service;
        $this->price_brand_repository   = $price_brand_repository;
        $this->price_model_repository   = $price_model_repository;
        $this->logger                   = $logger;
    
        $connection = $this->em->getConnection();
        $connection->getConfiguration()->setSQLLogger($sql_logger);
    }
    
    public function generateByNewBrand(PriceBrand $price_brand)
    {
        $brand_page     = $this->addRootBrandPage($price_brand);
        $price_services = $this->price_service_repository->findAll();
        foreach ($price_services as $price_service) {
            //echo $price_service->getName().' - '. $this->translate_service->transliterate($price_service->getName()).PHP_EOL;
            $this->addServicePage(
                $price_service,
                $brand_page,
                $price_brand->getBrandModelWithTranslation(),
                $price_brand->getName()
            );
        }
        $this->em->flush();
    }
    
    public function generateByNewModel(PriceModel $price_model)
    {
        $price_brand = $price_model->getPriceBrand();
        
        $brand_page = $this->brand_repository->findOneBy(['priceBrand' => $price_brand]);
        if (null === $brand_page) {
            $brand_page = $this->addRootBrandPage($price_brand);
        }
        $model_page     = $this->addRootModelPage($price_model, $price_brand, $brand_page);
        $price_services = $this->price_service_repository->findAll();
        foreach ($price_services as $price_service) {
            $this->addServicePage(
                $price_service,
                $model_page,
                $price_model->getBrandModelWithTranslation(),
                $price_brand->getName()
            );
            
        }
        $this->em->flush();
    }
    
    public function generateByNewService(PriceService $price_service)
    {
        $price_brands = $this->price_brand_repository->findAll();
        
        foreach ($price_brands as $price_brand) {
            $brand_page = $this->brand_repository->findOneBy(['priceBrand' => $price_brand]);
            if (null === $brand_page) {
                $brand_page = $this->addRootBrandPage($price_brand);
            }
            $this->addServicePage(
                $price_service,
                $brand_page,
                $price_brand->getBrandModelWithTranslation(),
                $price_brand->getName()
            );
            foreach ($price_brand->getPriceModels() as $price_model) {
                $model_page = $this->model_repository->findOneBy(['priceModel' => $price_model]);
                if (null === $model_page) {
                    $model_page = $this->addRootModelPage($price_model, $price_brand, $brand_page);
                }
                $this->addServicePage(
                    $price_service,
                    $model_page,
                    $price_model->getBrandModelWithTranslation(),
                    $price_brand->getName()
                );
            }
        }
        $this->em->flush();
        
    }
    
    public function generateByPageGeneratorModel(PageGeneratorModel $page_generator_model):int
    {
        $pages = $page_generator_model->getMatchedPages();
        foreach ($pages as $page) {
            $service = $page->getService();
            $brand_model = $page->getBrandModelWithTranslation();
            $brand = $page->getPriceBrand()->getName();
            $h1 = $this->renderSeoTemplate($service,$brand_model,$brand,$page_generator_model->h1);
            $metaDescription = $this->renderSeoTemplate($service,$brand_model,$brand,$page_generator_model->metaDescription);
            $text = $this->renderSeoTemplate($service,$brand_model,$brand,$page_generator_model->text);
            $this->regeneratePath($page_generator_model, $page);
            $page->setH1($h1);
            $page->setMetaDescription($metaDescription);
            $page->setText($text);
            $page->setPublished(true);
        }
        $this->em->flush();
        return count($pages);
    }
    
    private function getSeo($type, $service, $brand_model, $brand)
    {
        $template = $this->seo[$type];
        
        return $this->renderSeoTemplate($service,$brand_model,$brand,$template);
    }
    
    private function renderSeoTemplate($service,$brand_model,$brand,$template)
    {
        return str_replace(['SERVICE', 'BRAND MODEL', 'BRAND'], [$service, $brand_model, $brand], $template);
    }
    
    private function addServicePage(
        PriceService $price_service,
        Content $parent,
        $brand_model_with_translation,
        $brand_name
    ) {
        $service_page = new Service();
        $service_page->setName($price_service->getName())
                     ->setPath($this->getNewServicePath($parent,$price_service))
                     ->setParent($parent);
        $service_page->setService($price_service)
                     ->setPriceCategory($price_service->getPriceCategory())
                     ->setH1($this->getSeo('h1', $price_service->getName(),
                         $brand_model_with_translation, $brand_name))
                     ->setMetaDescription($this->getSeo('description', $price_service->getName(),
                         $brand_model_with_translation,
                         $brand_name))
                     ->setPublished(false);
        // $this->logger->info($service_page->getPath().' - '.$parent->getId());
        $this->em->persist($service_page);
    }
    
    /**
     * @param PriceBrand $brand
     *
     * @return Brand
     */
    private function addRootBrandPage(PriceBrand $brand): Brand
    {
        $main_page  = $this->content_repository->find(1);
        $brand_page = new Brand();
        $brand_page->setName($brand->getBrandModelWithTranslation())
                   ->setPath($brand->getPath())
                   ->setPriceBrand($brand)
                   ->setParent($main_page)
                   ->setH1($this->getSeo('h1', 'Кузовные работы', $brand->getBrandModelWithTranslation(),
                       $brand->getName()))
                   ->setMetaDescription($this->getSeo('description', 'Кузовные работы',
                       $brand->getBrandModelWithTranslation(),
                       $brand->getName()))
                   ->setPublished(true);
        $this->em->persist($brand_page);
        
        return $brand_page;
    }
    
    /**
     * @param PriceModel $price_model
     * @param PriceBrand $brand
     * @param Brand      $brand_page
     *
     * @return Model
     */
    private function addRootModelPage(PriceModel $price_model, PriceBrand $brand, Brand $brand_page): Model
    {
        $model_page = new Model();
        
        $model_page->setName($price_model->getBrandModelWithTranslation())
                   ->setPath($price_model->getPath())
                   ->setPriceModel($price_model)
                   ->setParent($brand_page)
                   ->setH1($this->getSeo('h1', 'Кузовные работы', $price_model->getBrandModelWithTranslation(),
                       $brand->getName()))
                   ->setMetaDescription($this->getSeo('description', 'Кузовные работы',
                       $price_model->getBrandModelWithTranslation(),
                       $brand->getName()))
                   ->setPublished(true);
        $this->em->persist($model_page);
        
        return $model_page;
    }

    private function getNewServicePath(Content $parent, PriceService $price_service, bool $isPlain = false):string
    {
        if ($isPlain) {
            return $parent->getPath() . $price_service->getSlug() . '/';
        }
        $parts = [];
        $parts[] = $price_service->getSlug();
        if ($price_service->getPriceCategory()) {
            $parts[] = $price_service->getPriceCategory()->getSlug();
            if ($price_service->getPriceCategory()->getParent()) {
                $parts[] = $price_service->getPriceCategory()->getParent()->getSlug();
            }
        }
        $parts = array_reverse(array_unique($parts));
        return $parent->getPath() . implode('/',$parts) . '/';
    }

    /**
     * @param PageGeneratorModel $page_generator_model
     * @param Service $page
     */
    private function regeneratePath(
        PageGeneratorModel $page_generator_model,
        Service $page
    ): void {
        if ($page_generator_model->urlRegenerate === PageGeneratorModel::URL_REGENERATE_ALL || ($page_generator_model->urlRegenerate === PageGeneratorModel::URL_REGENERATE_UNPUBLISHED && !$page->getPublished())) {
            $page->setPath($this->getNewServicePath($page->getParent(), $page->getService()));
        }
    }
}