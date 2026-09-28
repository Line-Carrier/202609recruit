<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 */
class OrdersRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Orders::class);
	}

	public function findByCustomerAndStatus(Customer $customer, ?int $status = null): array
	{
		$queryBuilder = $this->createQueryBuilder('o')
			->andWhere('o.owner = :customer')
			->setParameter('customer', $customer);

		if ($status !== null) {
			$queryBuilder
				->andWhere('o.status = :status')
				->setParameter('status', $status);
		}

		return $queryBuilder
			->getQuery()
			->getResult();
	}
}
