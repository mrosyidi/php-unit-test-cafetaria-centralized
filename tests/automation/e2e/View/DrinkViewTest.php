<?php 

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Service\DrinkServiceImpl;

    class DrinkViewTest extends TestCase
    {
        private string $path;
        private DrinkRepositoryImpl $drinkRepository;
        private DrinkServiceImpl $drinkService;

        public function setUp(): void 
        {
            $this->path = realpath(__DIR__ . "/../../../../app/App.php");

            $connection = Database::getConnection();

            $this->drinkRepository = new DrinkRepositoryImpl($connection);
            $this->drinkService = new DrinkServiceImpl($this->drinkRepository);

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

        public function testShowDrinkWhenNoFoodExists()
        {
            $output = $this->runCliApp([
                "2",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("Tidak ada daftar minuman", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testShowDrinkWhenFoodExists()
        {
            $this->drinkService->addDrink("Es Campur", 12000);

            $output = $this->runCliApp([
                "2",      
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("1. Es Campur  Rp.12000", $output);
        }

        public function testShowDrinkWithInvalidMenuSelection()
        {
            $output = $this->runCliApp([
                "2",      
                "6",
                "x",           
                "x"
            ]);

            $this->assertStringContainsString("Pilihan tidak dimengerti", $output);
            $this->assertStringContainsString("Sampai Jumpa Lagi", $output);
        }

        public function testAddDrinkWhenNameIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "x",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal menambah minuman.", $output);
        }

        public function testAddDrinkWithEmptyNameShouldFail()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah minuman, nama tidak boleh kosong.", $output);
        }

        public function testAddDrinkWithDuplicateNameShouldFail()
        {
            $this->drinkService->addDrink("Es Campur", 12000);

            $output = $this->runCliApp([
                "2",
                "1",      
                "Es Campur",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah minuman, nama minuman sudah ada.", $output);
        }

        public function testAddDrinkWhenPriceIsXShouldCancel()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "Es Oyen",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Batal menambah minuman", $output);
        }

        public function testAddDrinkWithEmptyPriceShouldFail()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "Jus Wortel",
                "",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah minuman, harga minuman harus bilangan.", $output);
        }

        public function testAddDrinkWithInvalidPriceShouldFail()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "Jus Semangka",
                "10000abc",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah minuman, harga minuman harus bilangan.", $output);
        }

        public function testAddDrinkWithDecimalPriceShouldFail()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "Es Campur",
                "12000.40",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah minuman, harga minuman harus bilangan bulat.", $output);
        }

        public function testAddDrinkWithZeroPriceShouldFail()
        {
            $output = $this->runCliApp([
                "2",
                "1",      
                "Jus Alpukat",
                "0",           
                "x",
                "x"
            ]);

            $this->assertStringContainsString("Gagal menambah minuman, harga minuman harus bilangan positif.", $output);
        }
    }