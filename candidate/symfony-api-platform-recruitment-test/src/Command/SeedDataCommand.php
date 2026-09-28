<?php

namespace App\Command;

use App\Entity\Customer;
use App\Entity\Orders;
use App\Entity\Product;
use App\Enum\OrderStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Seed random initial data into the database',
)]
class SeedDataCommand extends Command
{
    private readonly Generator $fakerGenerator;
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
        $this->fakerGenerator = Factory::create();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle( $input, $output );

        $io->title( 'Seeding random initial data into the database for development and testing purposes' );

        $seedEntityClasses = [
            Customer::class,
            Product::class,
            Orders::class,
        ];

        foreach ($seedEntityClasses as $entityClass) {
            $io->section( sprintf( 'Seeding data for %s', $entityClass ) );
            $this->seedDataForEntity($entityClass, $io);
        }

        return Command::SUCCESS;
    }

    private function seedDataForEntity(string $entityClass, SymfonyStyle $io): void
    {
        $rowsToSeed = rand(5, 15); // Random number of rows to seed for each entity

        for ($i = 0; $i < $rowsToSeed; $i++) {
            $entity = new $entityClass();
            // Populate the entity with random data using Faker
            // Example for Customer entity:
            if ($entity instanceof Customer) {
                $entity->setUuid(Uuid::v7());
                $entity->setRoles(['ROLE_USER']);
                $hashedPassword = $this->passwordHasher->hashPassword( $entity, $this->fakerGenerator->password() );
                $entity->setPassword($hashedPassword);
            }
            // Example for Product entity:
            if ($entity instanceof Product) {
                $vatRates = [0, 0.1, 0.135, 0.255];
                $entity->setUuid(Uuid::v7());
                $entity->setName($this->fakerGenerator->word());
                $entity->setPrice($this->fakerGenerator->randomFloat(2, 1, 100));
                $entity->setVatRate($this->fakerGenerator->randomElement($vatRates));
            }
            // Example for Orders entity:
            if ($entity instanceof Orders) {
                $entity->setUuid(Uuid::v7());
                $owner = $this->fakerGenerator->randomElement($this->entityManager->getRepository(Customer::class)->findAll());
                $products = $this->fakerGenerator->randomElements($this->entityManager->getRepository(Product::class)->findAll());
                $entity->setOwner($owner);
                $entity->setStatus($this->fakerGenerator->randomElement(OrderStatusEnum::cases())->value);
                $productsTotal = rand(1, 5);
                while ($productsTotal > 0) {
                    $product = $this->fakerGenerator->randomElement($products);
                    $entity->addProduct($product);
                    $productsTotal--;
                }
                // Ensure price is calculated after products are added
                $entity->initializePrice();
            }

            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
    }
}