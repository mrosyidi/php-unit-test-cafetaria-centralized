<?php 

    namespace Cafetaria\Service;

    use \Cafetaria\Entity\Detail;
    use \Cafetaria\Repository\DetailRepository;

    class DetailServiceImpl implements DetailService
    {
        private DetailRepository $detailRepository;

        public function __construct(DetailRepository $detailRepository)
        {
            $this->detailRepository = $detailRepository;
        }

        public function getAllDetail(): array
        {
            return $this->detailRepository->findAll();
        }

        public function addDetail(array $items): void
        {
            
        }
    }