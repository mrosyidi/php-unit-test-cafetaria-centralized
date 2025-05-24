<?php 

    namespace Cafetaria\Service;

    interface OrderService
    {
        public function getAllOrder(): array;
        public function addOrder(int $code, string $name, int $price, int $qty): void;
        public function removeOrder(int $code): void;
    }