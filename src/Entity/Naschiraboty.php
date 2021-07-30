<?php

namespace App\Entity;

use App\Entity\Contracts\PageInterface;
use App\Entity\Traits\PriceServicesListTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NaschirabotyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Naschiraboty implements PageInterface
{
    use Traits\ModifyDateTrait;
    use PriceServicesListTrait;

    const IMAGES_PATH = 'img/naschiraboty';
    public const BASE_PATH = '/naschiraboty/';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meta_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meta_description;

    /**
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     * @ORM\Column(type="integer")
     */
    private $sum = 0;
    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @ORM\Column(type="text")
     */
    private $short_text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client_name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PriceService")
     */
    private $priceServices;

    /**
     * @ORM\OneToMany(targetEntity=AttachNashiraboty::class, mappedBy="nashiraboty", cascade={"persist"})
     */
    private $attach;

    public function __construct()
    {
        $this->priceServices = new ArrayCollection();
        $this->attach = new ArrayCollection();
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

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function setMetaTitle(string $meta_title): self
    {
        $this->meta_title = $meta_title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getShortText(): ?string
    {
        return $this->short_text;
    }

    public function setShortText(string $short_text): self
    {
        $this->short_text = $short_text;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getImgFolder()
    {
        return self::IMAGES_PATH.'/'. $this->getId();
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

    /**
     * @return int
     */
    public function getSum(): int
    {
        return $this->sum;
    }

    /**
     * @param int $sum
     * @return $this
     */
    public function setSum(int $sum): self
    {
        $this->sum = $sum;
        return $this;
    }

    public function getPath(): ?string
    {
        return self::BASE_PATH . $this->getId() . '/';
    }

    public function getH1(): ?string
    {
        return $this->getName();
    }

    public function getBrandName(): string
    {
        return '';
    }

    /**
     * Возвращает путь из таблицы content или объект PageInterface
     * @return string|PageInterface
     */
    public function getParent()
    {
        return self::BASE_PATH;
    }

    public function getBrandAndModelName(): string
    {
        return '';
    }

    /**
     * @return Collection|AttachNashiraboty[]
     */
    public function getAttach(): Collection
    {
        return $this->attach;
    }

    public function addAttach(AttachNashiraboty $attach): self
    {
        if (!$this->attach->contains($attach)) {
            $this->attach[] = $attach;
            $attach->setNashiraboty($this);
        }

        return $this;
    }

    public function removeAttach(AttachNashiraboty $attach): self
    {
        if ($this->attach->removeElement($attach)) {
            // set the owning side to null (unless already changed)
            if ($attach->getNashiraboty() === $this) {
                $attach->setNashiraboty(null);
            }
        }

        return $this;
    }
}
