<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\Metadata\QueryParameter;
use App\Entity\Customer;
use App\Enum\OrderStatusEnum;
use App\Repository\OrdersRepository;
use App\State\CustomerOrdersProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
	normalizationContext: ['groups' => ['order:read'], 'serialize_payload_keys' => true],
	operations: [
		new Get(
			openapi: new Operation(
				summary: 'Retrieves a single Orders resource.',
				description: 'Return a single order.',
			),
		),
		new GetCollection(
			openapi: new Operation(
				summary: 'Retrieves the collection of Orders resources.',
				description: 'Return collection of orders.',
			),
		),
		new GetCollection(
			uriTemplate: '/customers/{id}/orders',
			provider: CustomerOrdersProvider::class,
			parameters: [
				'status' => new QueryParameter(
					description: 'Filter orders by status',
					schema: [
						'type' => 'string',
						'enum' => ['pending', 'processing', 'completed', 'cancelled'],
					],
				),
			],
			openapi: new Operation(
				summary: 'Retrieves orders for a customer.',
				description: 'Return orders belonging to a customer, optionally filtered by status.',
				security: [
					['JWT' => []],
				],
			),
		),
	]
)]
#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['order:read', 'customer:read'])]
	private ?int $id = null;

	#[ORM\Column(type: 'uuid')]
	#[Groups(['order:read', 'customer:read'])]
	private ?Uuid $uuid = null;

	#[ORM\Column]
	#[Groups(['order:read', 'customer:read'])]
	private ?int $status = null;

	/**
	 * @var Collection<int, Product>
	 */
	#[ORM\ManyToMany(targetEntity: Product::class, cascade: ['persist'])]
	#[Groups(['order:read', 'customer:read'])]
	private Collection $product;

	#[ORM\Column]
	#[Groups(['order:read', 'customer:read'])]
	private ?float $price = null;

	#[ORM\ManyToOne(inversedBy: 'orders')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['order:read'])]
	private ?Customer $owner = null;

	public function __construct()
	{
		$this->product = new ArrayCollection();
	}

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

	public function getStatus(): ?int
	{
		return $this->status;
	}

	public function setStatus(int $status): static
	{
		$this->status = $status;

		return $this;
	}

	#[ORM\PrePersist]
	public function initializeStatus(): static
	{
		if ($this->status === null) {
			$this->status = OrderStatusEnum::DRAFT->value;
		}

		return $this;
	}

	/**
	 * @return Collection<int, Product>
	 */
	public function getProduct(): Collection
	{
		return $this->product;
	}

	public function addProduct(Product $product): static
	{
		if (!$this->product->contains($product)) {
			$this->product->add($product);
		}

		return $this;
	}

	public function removeProduct(Product $product): static
	{
		$this->product->removeElement($product);

		return $this;
	}

	#[ORM\PrePersist]
	#[ORM\PreUpdate]
	public function initializePrice(): static
	{
		$products = $this->getProduct();
		$totalPrice = '0.0000000';
		foreach ($products as $product) {
			$price = $product->getPriceWithVat();
			if ($price !== null) {
				$totalPrice = bcadd($totalPrice, (string) $price, 7);
			}
		}
		// Round final price to 2 decimals with proper rounding
		$this->price = round((float) $totalPrice, 2, PHP_ROUND_HALF_UP);

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

	public function getOwner(): ?Customer
	{
		return $this->owner;
	}

	public function setOwner(Customer $owner): static
	{
		$this->owner = $owner;

		return $this;
	}
}
