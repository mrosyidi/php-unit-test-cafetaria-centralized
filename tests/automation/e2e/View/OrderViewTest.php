<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Service\OrderServiceImpl;

    class OrderViewTest extends TestCase
    {
        private string $path;
        private FoodRepositoryImpl $foodRepository;
        private DrinkRepositoryImpl $drinkRepository;
        private OrderRepositoryImpl $orderRepository;
        private FoodServiceImpl $foodService;
        private DrinkServiceImpl $drinkService;
        private OrderServiceImpl $orderService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->foodRepository = new FoodRepositoryImpl($connection);
            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->orderRepository = new OrderRepositoryImpl($connection);

            $this->foodService = new FoodServiceImpl($this->foodRepository);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);
            $this->orderService = new OrderServiceImpl($this->orderRepository);

            $this->orderRepository->removeAll();
            $this->foodRepository->removeAll();
            $this->drinkRepository->removeAll();
        }

        public function runCliApp(array $inputs): string
        {
            $descriptorspec = [
                0 => ["pipe", "r"],
                1 => ["pipe", "w"],
                2 => ["pipe", "w"]
            ];

            $process = proc_open("php " . $this->path , $descriptorspec, $pipes);

            if (!is_resource($process)) 
            {
                $this->fail("Gagal membuka proses App.php");
            }

            fwrite($pipes[0], implode(PHP_EOL , $inputs) . PHP_EOL);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            return $output;
        }

        public function testShowOrderWhenNoOrderExist()
        {
            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pesanan", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWhenOrderFoodExists()
        {
            $this->foodService->addFood("Mie Ayam", 7000);
            
            $foods = $this->foodService->getAllFood();
            
            $this->orderService->addOrder(1, $foods[0]->getName(), $foods[0]->getPrice(), 1);

            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("1. 1 Mie Ayam Rp.7000 (x1) Rp.7000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWhenOrderDrinkExists()
        {
            $this->drinkService->addDrink("Es Oyen", 12000);
            
            $drinks = $this->drinkService->getAllDrink();
            
            $this->orderService->addOrder(1, $drinks[0]->getName(), $drinks[0]->getPrice(), 2);

            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("1. 1 Es Oyen Rp.12000 (x2) Rp.24000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWhenOrderFoodAndDrinkExists()
        {
            $this->foodService->addFood("Mie Ayam", 7000);
            $this->drinkService->addDrink("Es Oyen", 12000);

            $foods = $this->foodService->getAllFood();
            $drinks = $this->drinkService->getAllDrink();
            
            $this->orderService->addOrder(1, $foods[0]->getName(), $foods[0]->getPrice(), 1);
            $this->orderService->addOrder(1, $drinks[0]->getName(), $drinks[0]->getPrice(), 2);

            $output = $this->runCliApp([
                "3",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("1. 1 Mie Ayam Rp.7000 (x1) Rp.7000", $output);
            $this->assertStringContainsString("2. 1 Es Oyen Rp.12000 (x2) Rp.24000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowOrderWithInvalidMenuSelection()
        {
            $output = $this->runCliApp([
                "3",      
                "7",
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pesanan", $output);
            $this->assertStringContainsString("Pilihan tidak dimengerti", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testAddOrderFoodWithEmptyNumberShouldFail()
        {
            $output = $this->runCliApp([
                "3",
                "1",      
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah pesanan, nomor makanan harus bilangan.", $output);
        }

        public function testAddOrderDrinkWithEmptyNumberShouldFail()
        {
            $output = $this->runCliApp([
                "3",
                "2",      
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah pesanan, nomor minuman harus bilangan.", $output);
        }

        public function testAddOrderFoodWhenNoFoodExists()
        {
            $output = $this->runCliApp([
                "3",
                "1",      
                "3",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah pesanan, tidak ada makanan dengan nomor 3.", $output);
        }

        public function testAddOrderDrinkWhenNoDrinkExists()
        {
            $output = $this->runCliApp([
                "3",
                "2",      
                "2",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah pesanan, tidak ada minuman dengan nomor 2.", $output);
        }

        public function testAddOrderFoodWhenNumberIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "3",
                "1",      
                "x",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal menambah pesanan.", $output);
        }

        public function testAddOrderDrinkWhenNumberIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "3",
                "2",      
                "x",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal menambah pesanan.", $output);
        }

        public function testAddOrderWithQtyIsXShouldCancelAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Mie Ayam",
                "7000",
                "x",
                "3",
                "1",  
                "1",    
                "x",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Mie Ayam  Rp.7000", $output);
            $this->assertStringContainsString("Batal menambah pesanan makanan.", $output);
        }

        public function testAddOrderWithQtyIsXShouldCancelAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Es Campur",
                "12000",
                "x",
                "3",
                "2",  
                "1",    
                "x",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Es Campur  Rp.12000", $output);
            $this->assertStringContainsString("Batal menambah pesanan minuman.", $output);
        }

        public function testAddOrderWithEmptyQtyShouldFailAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Rawon",
                "12000",
                "x",
                "3",
                "1",  
                "1",    
                "",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Rawon  Rp.12000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan makanan harus bilangan.", $output);
        }

        public function testAddOrderWithEmptyQtyShouldFailAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Es Oyen",
                "12000",
                "x",
                "3",
                "2",  
                "1",    
                "",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Es Oyen  Rp.12000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan minuman harus bilangan.", $output);
        }

        public function testAddOrderWithInvalidQtyShouldFailAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Soto Ayam",
                "10000",
                "x",
                "3",
                "1",  
                "1",    
                "b",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Soto Ayam  Rp.10000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan makanan harus bilangan.", $output);
        }

        public function testAddOrderWithInvalidQtyShouldFailAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Es Buah",
                "12000",
                "x",
                "3",
                "2",  
                "1",    
                "c",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Es Buah  Rp.12000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan minuman harus bilangan.", $output);
        }

        public function testAddOrderWithDecimalQtyShouldFailAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Pastel",
                "5000",
                "x",
                "3",
                "1",  
                "1",    
                "2.5",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Pastel  Rp.5000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan makanan harus bilangan bulat.", $output);
        }

        public function testAddOrderWithDecimalQtyShouldFailAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Jus Wortel",
                "6000",
                "x",
                "3",
                "2",  
                "1",    
                "3.45",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Jus Wortel  Rp.6000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan minuman harus bilangan bulat.", $output);
        }

        public function testAddOrderWithZeroQtyShouldFailAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Gado-Gado",
                "12000",
                "x",
                "3",
                "1",  
                "1",    
                "0",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Gado-Gado  Rp.12000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan makanan minimal satu.", $output);
        }

        public function testAddOrderWithZeroQtyShouldFailAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Es Teh",
                "4000",
                "x",
                "3",
                "2",  
                "1",    
                "0",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Es Teh  Rp.4000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan minuman minimal satu.", $output);
        }

        public function testAddOrderWithNegativeQtyShouldFailAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Ayam Goreng",
                "12000",
                "x",
                "3",
                "1",  
                "1",    
                "-2",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Ayam Goreng  Rp.12000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan makanan minimal satu.", $output);
        }

        public function testAddOrderWithNegativeQtyShouldFailAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Jus Melon",
                "8000",
                "x",
                "3",
                "2",  
                "1",    
                "-3",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Jus Melon  Rp.8000", $output);
            $this->assertStringContainsString("Gagal menambah pesanan, jumlah pesanan minuman minimal satu.", $output);
        }

        public function testAddOrderSuccessAfterAddFood()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Ayam Goreng",
                "12000",
                "x",
                "3",
                "1",  
                "1",    
                "2",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah makanan.", $output);
            $this->assertStringContainsString("1. Ayam Goreng  Rp.12000", $output);
            $this->assertStringContainsString("Sukses menambah pesanan.", $output);
            $this->assertStringContainsString("1. 1 Ayam Goreng Rp.12000 (x2) Rp.24000", $output);
        }

        public function testAddOrderSuccessAfterAddDrink()
        {
            $output = $this->runCliApp([
                "2",
                "1",
                "Es Kelapa Muda",
                "8000",
                "x",
                "3",
                "2",  
                "1",    
                "2",           
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Sukses menambah minuman.", $output);
            $this->assertStringContainsString("1. Es Kelapa Muda  Rp.8000", $output);
            $this->assertStringContainsString("Sukses menambah pesanan.", $output);
            $this->assertStringContainsString("1. 1 Es Kelapa Muda Rp.8000 (x2) Rp.16000", $output);
        }

        public function testAddOrderSuccessWithoutExist()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Soto Ayam",
                "10000",
                "x",

                "2",
                "1",
                "Es Campur",
                "12000",
                "x",

                "3",
                "1",
                "1",
                "2",

                "2",  
                "1",    
                "2",

                "x",
                "x"
            ]);

            $this->assertStringContainsString("1. 1 Soto Ayam Rp.10000 (x2) Rp.20000", $output);
            $this->assertStringContainsString("2. 1 Es Campur Rp.12000 (x2) Rp.24000", $output);
        }

        public function testAddOrderSuccessWithExist()
        {
            $output = $this->runCliApp([
                "1",
                "1",
                "Soto Ayam",
                "10000",
                "x",

                "2",
                "1",
                "Es Campur",
                "12000",

                "1",
                "Es Oyen",
                "12000",
                "x",

                "3",
                "1",
                "1",
                "2",

                "2",  
                "1",    
                "2",
                "x",

                "3",
                "2",
                "2",
                "1",

                "x",
                "x"
            ]);

            $this->assertStringContainsString("1. 1 Soto Ayam Rp.10000 (x2) Rp.20000", $output);
            $this->assertStringContainsString("2. 1 Es Campur Rp.12000 (x2) Rp.24000", $output);
            $this->assertStringContainsString("3. 2 Es Oyen Rp.12000 (x1) Rp.12000", $output);
        }
    }