<?php 

    namespace Cafetaria\Service;

    interface FoodService 
    {
        public function getAllFood(): array;
        public function addFood(string $name, int $price): void;
        public function removeFood(string $name): bool;
    }