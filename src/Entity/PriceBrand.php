<?php

namespace App\Entity;

use App\Entity\Traits\VichImagePropertyNamerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * PriceBrand
 *
 * @ORM\Table(name="price__brand")
 * @ORM\Entity(repositoryClass="App\Repository\PriceBrandRepository")
 * @ORM\EntityListeners({"App\Doctrine\GeneratePagesByPriceBrandListener"})
 * @Vich\Uploadable
 */
class PriceBrand
{
    use VichImagePropertyNamerTrait;

    const DEFAULT_PRICE_OF_HOUR = 1600;
    const DEFAULT_INCREASE = 0;
    const DEFAULT_IMAGE = '/img/no_image_car.png';
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="increase", type="float", nullable=false)
     */
    private $increase;
    /**
     * @var PriceModel[]
     *
     * @ORM\OneToMany(targetEntity="PriceModel",mappedBy="priceBrand")
     */
    private $priceModels;
    

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 0;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameRus;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Salon", mappedBy="excludedBrands")
     */
    private $excludedSalons;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PriceClass")
     * @ORM\JoinColumn(nullable=false,name="class")
     */
    private $class;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @var bool
     */
    private $popular = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @Vich\UploadableField(mapping="web_root_photo", fileNameProperty="photo")
     * @var File
     */
    protected $photoFile;

    public function __construct()
    {
        $this->priceModels = new ArrayCollection();
        $this->excludedSalons = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIncrease(): ?float
    {
        return $this->increase;
    }

    public function setIncrease(float $increase): self
    {
        $this->increase = $increase;

        return $this;
    }
    
    public function getAlias():string
    {
        return str_replace(' ', '-', mb_strtolower($this->getName()));
    }

    public function getPath(): string
    {
        return '/' . $this->getCode() . '/';
    }

    /**
     * @return Collection|PriceModel[]
     */
    public function getPriceModels(): Collection
    {
        return $this->priceModels;
    }

    /**
     * @param array $types
     * @param bool $replace - заменить полный список моделей отфильтрованным списком?
     * @return Collection|PriceModel[]
     */
    public function filterPriceModelsByTypes(array $types, bool $replace = true): Collection
    {
        $filteredModels = $this->priceModels->filter(static function (PriceModel $priceModel) use ($types) {
            return in_array($priceModel->getType(), $types, true);
        });
        if ($replace) {
            $this->priceModels = $filteredModels;
        }
        return $filteredModels;
    }

    /**
     * @param bool $replace - заменить полный список моделей отфильтрованным списком?
     * @return Collection|PriceModel[]
     */
    public function filterPriceModelsByPopular(bool $replace = true): Collection
    {
        $filteredModels = $this->priceModels->filter(static function (PriceModel $priceModel) {
            return $priceModel->isPopular();
        });

        if ($replace) {
            $this->priceModels = $filteredModels;
        }
        return $filteredModels;
    }

    public function addPriceModel(PriceModel $priceModel): self
    {
        if (!$this->priceModels->contains($priceModel)) {
            $this->priceModels[] = $priceModel;
            $priceModel->setPriceBrand($this);
        }
        
        return $this;
    }
    
    public function removePriceModel(PriceModel $priceModel): self
    {
        if ($this->priceModels->contains($priceModel)) {
            $this->priceModels->removeElement($priceModel);
            // set the owning side to null (unless already changed)
            if ($priceModel->getPriceBrand() === $this) {
                $priceModel->setPriceBrand(null);
            }
        }
        
        return $this;
    }
    
    public function __toString()
    {
        return (string)$this->name;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
    
    public function getNameRus(): ?string
    {
        return $this->nameRus;
    }
    
    public function setNameRus(?string $nameRus): self
    {
        $this->nameRus = $nameRus;
        
        return $this;
    }
    
    public function getCode(): ?string
    {
        return $this->code;
    }
    
    public function setCode(?string $code): self
    {
        $this->code = $code;
        
        return $this;
    }
    
    public function getBrandModelWithTranslation()
    {
        return $this->getName().' ('. $this->getNameRus().')';
    }

    /**
     * @return Collection|Salon[]
     */
    public function getExcludedSalons(): Collection
    {
        return $this->excludedSalons;
    }

    public function addExcludedSalon(Salon $excludedSalon): self
    {
        if (!$this->excludedSalons->contains($excludedSalon)) {
            $this->excludedSalons[] = $excludedSalon;
            $excludedSalon->addExcludedBrand($this);
        }

        return $this;
    }

    public function removeExcludedSalon(Salon $excludedSalon): self
    {
        if ($this->excludedSalons->contains($excludedSalon)) {
            $this->excludedSalons->removeElement($excludedSalon);
            $excludedSalon->removeExcludedBrand($this);
        }

        return $this;
    }

    public function getClass(): ?PriceClass
    {
        return $this->class;
    }

    public function setClass(?PriceClass $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Example:
     * return 'img/product/';
     * @return string
     */
    public function getImgFolder(): string
    {
        return 'img/marks2/';
    }

    /**
     * Example:
     * return $this->getSlug();
     * @return string
     */
    public function getImgName(): string
    {
        return $this->getAlias();
    }

    /**
     * @return bool
     */
    public function isPopular(): bool
    {
        return $this->popular;
    }

    /**
     * @param bool $popular
     * @return $this
     */
    public function setPopular(bool $popular): self
    {
        $this->popular = $popular;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function setPhotoFile(File $photo = null)
    {
        $this->photoFile = $photo;
        if ($photo) {
            $this->modifyDate = new \DateTime('now');
        }
    }

    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @return string
     */
    public function getPhotoFolder():string
    {
        return 'img/brand-photo/';
    }

    public function getPhotoUrl()
    {
        if ( ! $this->getPhoto()) {
            return self::DEFAULT_IMAGE;
        }
        return '/'.$this->getPhotoFolder(). $this->getPhoto();
    }
}
