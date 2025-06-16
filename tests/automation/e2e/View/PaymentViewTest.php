<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Service\PaymentServiceImpl;

    class PaymentViewTest extends TestCase
    {
        private string $path;
        private FoodRepositoryImpl $foodRepository;
        private DrinkRepositoryImpl $drinkRepository;
        private OrderRepositoryImpl $orderRepository;
        private PaymentRepositoryImpl $paymentRepository;
        private FoodServiceImpl $foodService;
        private DrinkServiceImpl $drinkService;
        private OrderServiceImpl $orderService;
        private PaymentServiceImpl $paymentService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->foodRepository = new FoodRepositoryImpl($connection);
            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->orderRepository = new OrderRepositoryImpl($connection);
            $this->paymentRepository = new PaymentRepositoryImpl($connection);

            $this->foodService = new FoodServiceImpl($this->foodRepository);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);
            $this->orderService = new OrderServiceImpl($this->orderRepository);
            $this->paymentService = new PaymentServiceImpl($this->paymentRepository);

            $this->orderRepository->removeAll();
            $this->foodRepository->removeAll();
            $this->drinkRepository->removeAll();
            $this->paymentRepository->removeAll();
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

        public function testShowPaymentWhenNoOrderExist()
        {
            $output = $this->runCliApp([
                "4",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pesanan", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowPaymentWhenOrderExists()
        {
            $this->foodService->addFood("Rawon", 12000);
            $this->foodService->addFood("Soto Ayam", 10000);
            $this->drinkService->addDrink("Es Oyen", 12000);
            $this->drinkService->addDrink("Es Campur", 12000);
            
            $foods = $this->foodService->getAllFood();
            $drinks = $this->drinkService->getAllDrink();
            
            $this->orderService->addOrder(1, $foods[0]->getName(), $foods[0]->getPrice(), 1);
            $this->orderService->addOrder(1, $foods[1]->getName(), $foods[1]->getPrice(), 1);
            $this->orderService->addOrder(1, $drinks[0]->getName(), $drinks[0]->getPrice(), 2);
            $this->orderService->addOrder(2, $drinks[1]->getName(), $drinks[1]->getPrice(), 1);

            $this->paymentService->addPayment(1, 44000, 100000, 56000);

            $output = $this->runCliApp([
                "4",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("1. 1 Rawon Rp.12000 (x1) Rp.12000", $output);
            $this->assertStringContainsString("2. 1 Soto Ayam Rp.10000 (x1) Rp.10000", $output);
            $this->assertStringContainsString("3. 1 Es Oyen Rp.12000 (x2) Rp.24000", $output);
            $this->assertStringContainsString("4. 2 Es Campur Rp.12000 (x1) Rp.12000", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowPaymentWithInvalidMenuSelection()
        {
            $output = $this->runCliApp([
                "4",      
                "7",
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("DAFTAR PESANAN", $output);
            $this->assertStringContainsString("Tidak ada daftar pesanan", $output);
            $this->assertStringContainsString("Pilihan tidak dimengerti", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testAddPaymentWithEmptyCodeShouldFail()
        {
            $output = $this->runCliApp([
                "4",
                "1",      
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal memproses pesanan, kode pesanan harus bilangan.", $output);
        }

        public function testAddPaymentWhenNoOrderExists()
        {
            $output = $this->runCliApp([
                "4",
                "1",
                "2",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal memproses pesanan, tidak ada item yang dipesan.", $output);
        }

        public function testAddPaymentWhenCodeIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "4",
                "1",      
                "x",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal memproses pesanan.", $output);
        }

        public function testAddPaymentWithCodeNotFoundShouldCancel()
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
                "1",
                "x",
                "4",
                "1",
                "5",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal memproses pesanan, kode pesanan tidak ditemukan.", $output);
        }

        public function testAddPaymentWithPayIsXShouldCancel()
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
                "1",
                "x",
                "4",
                "1",
                "1",
                "x",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Total yang harus dibayar : Rp.12000", $output);
            $this->assertStringContainsString("Batal memproses pesanan.", $output);
        }

        public function testAddPaymentWithEmptyPayShouldFail()
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
                "1",
                "x",
                "4",
                "1",
                "1",
                "",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Total yang harus dibayar : Rp.5000", $output);
            $this->assertStringContainsString("Gagal memproses pesanan, jumlah uang harus bilangan.", $output);
        }

        public function testAddPaymentWithInvalidPayShouldCancel()
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
                "1",
                "x",
                "4",
                "1",
                "1",
                "fffff",
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Total yang harus dibayar : Rp.5000", $output);
            $this->assertStringContainsString("Gagal memproses pesanan, jumlah uang harus bilangan.", $output);
        }
    }