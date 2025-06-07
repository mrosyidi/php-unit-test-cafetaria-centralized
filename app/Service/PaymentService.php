<?php 

    namespace Cafetaria\Service;

    interface PaymentService 
    {
        public function getAllPayment(): array;
        public function addPayment(int $code, int $total, int $pay): void;
    }