<?php 

    namespace Cafetaria\Repository;

    use Cafetaria\Entity\Drink;

    interface DrinkRepository
    {
        public function findAll(): array;
        public function save(Drink $drink): void;
        public function remove(string $name): bool;
        public function removeAll(): void;
    }