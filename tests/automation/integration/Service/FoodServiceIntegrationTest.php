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

        public function testAddFoodSuccess()
        {
            $this->foodService->addFood("Soto Daging", 12000);

            $foods = $this->foodService->getAllFood();

            $this->assertCount(1, $foods);
            $this->assertEquals("Soto Daging", $foods[0]->getName());
            $this->assertEquals(12000, $foods[0]->getPrice());
        }

        public function testAddFoodWithEmptyNameThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Nama tidak boleh kosong.");

            $this->foodService->addFood("", 10000); 
        }

        public function testAddFoodWithZeroPriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->foodService->addFood("Gado-Gado", 0); 
        }

        public function testAddFoodWithNegativePriceThrowsException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Harga harus lebih dari nol.");

            $this->foodService->addFood("Gado-Gado", -12000); 
        }

        public function testRemoveFoodSuccess()
        {
            $this->foodService->addFood("Pastel", 5000);
            
            $foods = $this->foodService->getAllFood();
            
            $this->assertCount(1, $foods);

            $result = $this->foodService->removeFood("Pastel");

            $this->assertTrue($result);

            $foods = $this->foodService->getAllFood();

            $this->assertCount(0, $foods);
        }

        public function testRemoveFoodReturnsFalseWhenFoodNotExist()
        {
            $result = $this->foodService->removeFood("Soto Daging");
            
            $this->assertFalse($result);
        }
    }