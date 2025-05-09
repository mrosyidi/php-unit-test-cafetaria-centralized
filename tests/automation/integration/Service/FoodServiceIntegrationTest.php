<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Entity\Food;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;

    class FoodServiceIntegrationTest extends TestCase 
    {
        private $foodRepository;
        private $foodService;

        public function setUp(): void 
        {
            $connection = Database::getConnection();
            $this->foodRepository = new FoodRepositoryImpl($connection);
            $this->foodService = new FoodServiceImpl($this->foodRepository);
            $this->foodRepository->removeAll();
        }

        public function testGetAllFoodData()
        {
            $food = new Food("Mie Ayam", 7000);
            
            $this->foodRepository->save($food);

            $result = $this->foodService->getAllFood();

            $this->assertCount(1, $result);
            $this->assertEquals($food->getName(), $result[0]->getName());
            $this->assertEquals($food->getPrice(), $result[0]->getPrice());
        }
    }