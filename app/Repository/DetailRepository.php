<?php 

    namespace Cafetaria\Repository;

    use Cafetaria\Entity\Detail;

    interface DetailRepository
    {
        public function findAll(): array;
        public function save(Detail $detail): void;
        public function removeAll(): void;
    }