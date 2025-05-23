<?php 

    namespace Cafetaria\Repository;

    use \Cafetaria\Entity\Order;

    interface OrderRepository
    {
        public function findAll(): array;
        public function save(Order $order): void;
        public function remove(int $code): void;
        public function removeAll(): void;
    }