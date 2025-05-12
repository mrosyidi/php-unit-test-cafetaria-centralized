<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;

    class FoodViewTest extends TestCase
    {
        private string $path;
        private FoodRepositoryImpl $foodRepository;
        private FoodServiceImpl $foodService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->foodRepository = new FoodRepositoryImpl($connection);
            $this->foodService = new FoodServiceImpl($this->foodRepository);

            $this->foodRepository->removeAll();
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

        public function testShowFoodWhenNoFoodExists()
        {
            $output = $this->runCliApp([
                "1",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("Tidak ada daftar makanan", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowFoodWhenFoodExists()
        {
            $this->foodService->addFood("Rawon", 12000);

            $output = $this->runCliApp([
                "1",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("1. Rawon  Rp.12000", $output);
        }

        public function testShowFoodWithInvalidMenuSelection()
        {
            $output = $this->runCliApp([
                "1",      
                "9",
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("Pilihan tidak dimengerti", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testAddFoodWhenNameIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "x",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal menambah makanan.", $output);
        }

        public function testAddFoodWithEmptyNameShouldFail()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, nama tidak boleh kosong.", $output);
        }

        public function testAddFoodWithDuplicateNameShouldFail()
        {
            $this->foodService->addFood("Sate Ayam", 12000);

            $output = $this->runCliApp([
                "1",
                "1",      
                "Sate Ayam",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, nama makanan sudah ada.", $output);
        }

        public function testAddFoodWhenPriceIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "Mie Ayam",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal menambah makanan.", $output);
        }

        public function testAddFoodWithEmptyPriceShouldFail()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "Mie Ayam",
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, harga makanan harus bilangan.", $output);
        }

        public function testAddFoodWithInvalidPriceShouldFail()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "Mie Ayam",
                "10000abc",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, harga makanan harus bilangan.", $output);
        }

        public function testAddFoodWithDecimalPriceShouldFail()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "Mie Ayam",
                "12000.40",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, harga makanan harus bilangan bulat.", $output);
        }

        public function testAddFoodWithZeroPriceShouldFail()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "Mie Ayam",
                "0",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, harga makanan harus bilangan positif.", $output);
        }

        public function testAddFoodWithNegativePriceShouldFail()
        {
            $output = $this->runCliApp([
                "1",
                "1",      
                "Mie Ayam",
                "-7000",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah makanan, harga makanan harus bilangan positif.", $output);
        }
    }
