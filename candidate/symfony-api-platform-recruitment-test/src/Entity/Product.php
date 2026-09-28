<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\OpenApi\Model\Operation;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    normalizationContext: ['groups' => ['product:read', 'order:read']],
    operations: [
        new Get(
            openapi: new Operation(
                summary: 'Retrieves a single Product resource.',
                description: 'Return a single product.',
            ),
        ),
        new GetCollection(
            openapi: new Operation(
                summary: 'Retrieves the collection of Product resources.',
                description: 'Return collection of products.',
            ),
        ),
    ]
)]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read', 'product:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups(['order:read', 'product:read'])]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order:read', 'product:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['order:read', 'product:read'])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['order:read', 'product:read'])]
    private ?float $vatRate = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    #[ORM\PrePersist]
    public function initializeUuid(): static
    {
        if ($this->uuid === null) {
            $this->uuid = Uuid::v7();
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    #[Groups(['order:read', 'product:read'])]
    public function getPriceWithVat(): ?float
    {
        if ($this->price === null || $this->vatRate === null) {
            return null;
        }

        $vat = bcmul($this->price, (string) $this->vatRate, 7);
        $priceWithVat = bcadd($this->price, $vat, 7);
        return round((float) $priceWithVat, 2, PHP_ROUND_HALF_UP);
    }

    public function getVatRate(): ?float
    {
        return $this->vatRate;
    }

    public function setVatRate(float $vatRate): static
    {
        $this->vatRate = $vatRate;

        return $this;
    }
}
