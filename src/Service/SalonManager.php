<?php


namespace App\Service;


use App\Entity\Brand;
use App\Entity\Contracts\PageInterface;
use App\Entity\Model;
use App\Entity\RootService;
use App\Entity\Salon;
use App\Entity\Service;
use App\Repository\SalonRepository;

class SalonManager
{
    /**
     * @var SalonRepository
     */
    private $salonRepository;
    /**
     * @var array|Salon[]
     */
    private $availableSalons = [];

    public function __construct(SalonRepository $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    /**
     * @param PageInterface|null $content
     * @return array|Salon[]
     */
    public function getSalonsByPage(?PageInterface $content): array
    {
        if (!empty($this->availableSalons)) {
            return $this->availableSalons;
        }
        //$allSalons = $this->salonRepository->findAllWithExcludes();
        $allSalons = $this->salonRepository->findAllPublishedWithExcludes();
        if (null === $content || !in_array(get_class($content), [
                Brand::class,
                Model::class,
                Service::class,
                RootService::class
            ])) {
            foreach ($allSalons as $availableSalon) {
                $availableSalon->setAlias(str_replace('BRAND','',$availableSalon->getAlias()));
            }
            return $allSalons;
        }
        $this->availableSalons = array_filter($allSalons, function (Salon $salon) use ($content) {
            if ($content->getPriceBrand() && $salon->getExcludedBrands()->contains($content->getPriceBrand())) {
                return false;
            }
            if ($content->getPriceModel() && $salon->getExcludedModels()->contains($content->getPriceModel())) {
                return false;
            }
            if ($content->getService() && $salon->getExcludedServices()->contains($content->getService())) {
                return false;
            }

            return true;
        });
        foreach ($this->availableSalons as $availableSalon) {
            $availableSalon->setAlias(str_replace('BRAND',$content->getBrandName(),$availableSalon->getAlias()));
        }
        return $this->availableSalons;
    }
}