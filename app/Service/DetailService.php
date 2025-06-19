<?php 

    namespace Cafetaria\Service;

    use Cafetaria\Repository\DetailRepository;

    interface DetailService
    {
        public function getAllDetail(): array;
        public function addDetail(array $items): void;
    }