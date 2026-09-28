<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\ParameterNotFound;
use App\Repository\CustomerRepository;
use App\Repository\OrdersRepository;
use App\Enum\OrderStatusEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CustomerOrdersProvider implements ProviderInterface
{
	public function __construct(
		private CustomerRepository $customerRepository,
		private OrdersRepository $ordersRepository
	) {
	}

	public function provide(
		Operation $operation,
		array $uriVariables = [],
		array $context = []
	): object|array|null {
		$customer = $this->customerRepository->find($uriVariables['id']);

		if ($customer === null) {
			throw new NotFoundHttpException('Customer not found.');
		}

		$statusParameter = $operation->getParameters()->get('status');
		$statusName = $statusParameter?->getValue();

		if ($statusName instanceof ParameterNotFound) {
			$statusName = null;
		}

		$status = null;

		if ($statusName !== null) {
			$status = match ($statusName) {
				'pending' => OrderStatusEnum::PENDING->value,
				'processing' => OrderStatusEnum::PROCESSING->value,
				'completed' => OrderStatusEnum::COMPLETED->value,
				'cancelled' => OrderStatusEnum::CANCELLED->value,
			};
		}

		return $this->ordersRepository->findByCustomerAndStatus($customer, $status);
	}
}