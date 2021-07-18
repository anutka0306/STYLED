<?php

namespace App\Entity;

use App\Entity\Traits\PriceServicesListTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * @ORM\Entity(repositoryClass="App\Repository\OurWorksRepository")
 */
class OurWorks
{
    use PriceServicesListTrait;
    const IMAGES_PATH = 'img/our-works';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PriceModel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $priceModel;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PriceService")
     */
    private $priceServices;

    /**
     *
     *
     * @var Collection|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName;


    public function setImageFile(?Array $imageFile = null): void
    {
        $fs = new Filesystem();
        $fs->mkdir(self::IMAGES_PATH.'/'.$this->getId());
        $this->imageFile = $imageFile;

    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function __construct()
    {
        $this->priceServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceModel(): ?PriceModel
    {
        return $this->priceModel;
    }

    public function setPriceModel(?PriceModel $priceModel): self
    {
        $this->priceModel = $priceModel;

        return $this;
    }

    /**
     * @return Collection|PriceService[]
     */
    public function getPriceServices(): Collection
    {
        return $this->priceServices;
    }

    public function addPriceService(PriceService $priceService): self
    {
        if (!$this->priceServices->contains($priceService)) {
            $this->priceServices[] = $priceService;
        }

        return $this;
    }

    public function removePriceService(PriceService $priceService): self
    {
        if ($this->priceServices->contains($priceService)) {
            $this->priceServices->removeElement($priceService);
        }

        return $this;
    }
    
    public function getPriceBrand(): ?PriceBrand
    {
        return $this->getPriceModel()->getPriceBrand();
    }
    
    public function getImgFolder()
    {
        return self::IMAGES_PATH.'/'. $this->getId();
    }
    
}
