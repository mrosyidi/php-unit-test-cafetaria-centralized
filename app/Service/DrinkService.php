<?php 

    namespace Cafetaria\Service;

    interface DrinkService 
    {
        public function getAllDrink(): array;
        public function addDrink(string $name, int $price): void;
        public function removeDrink(string $name): bool;
    }