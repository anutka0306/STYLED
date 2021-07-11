<?php

namespace App\Model;

use App\Dto\YmlOfferDto;
use App\Repository\ServiceRepository;
use App\Service\PriceListHelper;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class YmlModel
{
    /**
     * @var Connection
     */
    protected $connection;
    /**
     * @var ServiceRepository
     */
    protected $service_repository;
    /**
     * @var PriceListHelper
     */
    protected $price_list_helper;
    
    public function __construct(Connection $connection,ServiceRepository $service_repository, PriceListHelper $price_list_helper)
    {
        
        $this->connection = $connection;
        $this->service_repository = $service_repository;
        $this->price_list_helper = $price_list_helper;
    }
    
    /**
     * @return YmlOfferDto[]
     */
    public function getOffers():array
    {
        $offers=[];
        $pages = $this->service_repository->findBy(['published'=>true]);
        foreach ($pages as $index => $page) {
            $offer = new YmlOfferDto();
            $offer->id = $page->getId();
            $offer->path = $page->getPath();
            $offer->meta_title = htmlspecialchars($page->getMetaTitle());
            $offer->meta_description = $page->getMetaDescription();
            $offer->price = $this->price_list_helper->getPrice($page);
            $offers[] = $offer;
        }
        return $offers;
    }
    
    public function getMainPageData()
    {
        $query  = $this->connection
            ->createQueryBuilder()
            ->select('c.meta_title, c.meta_description')
            ->from('content','c')
            ->andWhere("path = '/'");
        $result = $query->execute();
        $result->setFetchMode(FetchMode::STANDARD_OBJECT);
        return $result->fetch();
    }
    
}