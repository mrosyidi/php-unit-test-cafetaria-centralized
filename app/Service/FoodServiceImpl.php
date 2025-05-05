<?php 
    
    namespace Cafetaria\Service;

    use Cafetaria\Entity\Food;
    use Cafetaria\Repository\FoodRepository;
    use Cafetaria\Service\FoodService;

    class FoodServiceImpl implements FoodService
    {
        private FoodRepository $foodRepository;

        public function __construct(FoodRepository $foodRepository)
        {
            $this->foodRepository = $foodRepository;
        }

        public function getAllFood(): array 
        {
            return $this->foodRepository->findAll();
        }

        public function addFood(string $name, int $price): void
        {

        }

        public function getFood(): array
        {
            
        }

        public function removeFood(string $name): bool
        {
            
        }
    }
