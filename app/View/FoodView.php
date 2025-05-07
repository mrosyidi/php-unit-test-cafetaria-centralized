<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\FoodService;
    use Cafetaria\Helper\CheckHelper;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\Validator\FoodValidator;

    class FoodView 
    {
        private FoodService $foodService;

        public function __construct(FoodService $foodService)
        {
            $this->foodService = $foodService;
        }

        public function showFood(): void 
        {
            while(true)
            {
                echo "DAFTAR MAKANAN" . PHP_EOL;

                $foods = $this->foodService->getAllFood();


                if(empty($foods))
                {
                    echo "Tidak ada daftar makanan" . PHP_EOL;
                }else
                {
                    foreach($foods as $number => $food)
                    {
                        $number++;
                        echo "$number. " . $food->getName() . "  Rp." . $food->getPrice() . PHP_EOL;
                    }
                }

                echo "Menu Makanan" . PHP_EOL;
                echo "1. Tambah Makanan" . PHP_EOL;
                echo "2. Hapus Makanan" . PHP_EOL;
                echo "x. Kembali" . PHP_EOL;

                $pilihan = InputHelper::input("Pilih");

                if($pilihan == "1")
                {

                }else if($pilihan == "2")
                {

                }else if($pilihan == "x")
                {
                    break;
                }else 
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }

        public function addFood(): void 
        {
            $foods = $this->foodService->getAllFood();

            echo "MENAMBAH MAKANAN" . PHP_EOL;

            $name = InputHelper::input("Nama makanan (x untuk batal)");

            if($name == "x")
            {
                echo "Batal menambah makanan." . PHP_EOL;
                return;
            }
            
            if(!FoodValidator::isValidName($name))
            {
                echo "Gagal menambah makanan, nama tidak boleh kosong." . PHP_EOL;
                return;
            }

            if(FoodValidator::isDuplicate($foods, $name))
            {
                echo "Gagal menambah makanan, nama makanan sudah ada." . PHP_EOL;
                return;
            }

            $price = InputHelper::input("Harga makanan (x untuk batal)");

            if($price == "x")
            {
                echo "Batal menambah makanan." . PHP_EOL;
                return;
            }

            if(!is_numeric($price))
            {
                echo "Gagal menambah makanan, harga makanan harus bilangan." . PHP_EOL;
                return;
            }

            $price = (float)$price;

            if($price <= 0)
            {
                echo "Gagal menambah makanan, harga harus bilangan positif." . PHP_EOL;
                return;
            }

            $this->foodService->addFood($name, $price);

            echo "Sukses menambah makanan" . PHP_EOL;
        }
    }