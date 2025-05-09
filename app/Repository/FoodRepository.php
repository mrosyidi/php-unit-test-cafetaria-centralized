<?php 

    namespace Cafetaria\Repository;

    use Cafetaria\Entity\Food;

    interface FoodRepository
    {
        public function findAll(): array;
        public function save(Food $food): void;
        public function remove(string $name): bool;
        public function removeAll(): void;
    }