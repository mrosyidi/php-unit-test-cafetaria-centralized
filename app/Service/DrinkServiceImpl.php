<?php 
    
    namespace Cafetaria\Service;

    use Cafetaria\Entity\Drink;
    use Cafetaria\Repository\DrinkRepository;
    use Cafetaria\Service\DrinkService;

    class DrinkServiceImpl implements DrinkService
    {
        private DrinkRepository $drinkRepository;

        public function __construct(DrinkRepository $drinkRepository)
        {
            $this->drinkRepository = $drinkRepository;
        }

        public function getAllDrink(): array 
        {
            return $this->drinkRepository->findAll();
        }

        public function addDrink(string $name, int $price): void
        {
            
        }

        public function removeDrink(string $name): bool
        {
            
        }
    }
