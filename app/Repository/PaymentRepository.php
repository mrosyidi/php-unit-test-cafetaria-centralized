<?php 

    namespace Cafetaria\Repository;

    use Cafetaria\Entity\Payment;

    interface PaymentRepository
    {
        public function findAll(): array;
        public function save(Payment $payment): void;
        public function removeAll(): void;
    }