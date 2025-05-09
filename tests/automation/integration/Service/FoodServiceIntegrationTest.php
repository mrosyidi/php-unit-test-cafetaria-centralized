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

        public function testGetAllFoodWithData()
        {
            $food = new Food("Mie Ayam", 7000);
            
            $this->foodRepository->save($food);

            $foods = $this->foodService->getAllFood();

            $this->assertCount(1, $foods);
            $this->assertEquals($food->getName(), $foods[0]->getName());
            $this->assertEquals($food->getPrice(), $foods[0]->getPrice());
        }

        public function testGetAllFoodWithNoData()
        {
            $foods = $this->foodService->getAllFood();

            $this->assertEmpty($foods);
        }
    }