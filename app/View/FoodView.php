<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\FoodService;
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
                    $this->addFood();
                }else if($pilihan == "2")
                {
                    $this->removeFood();
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

            if (filter_var($price, FILTER_VALIDATE_INT) === false) 
            {
                echo "Gagal menambah makanan, harga makanan harus bilangan bulat." . PHP_EOL;
                return;
            }
            
            $price = (int)$price;  

            if($price <= 0)
            {
                echo "Gagal menambah makanan, harga makanan harus bilangan positif." . PHP_EOL;
                return;
            }

            $this->foodService->addFood($name, $price);

            echo "Sukses menambah makanan." . PHP_EOL;
        }

        public function removeFood(): void 
        {
            echo "MENGHAPUS MAKANAN" . PHP_EOL;

            $number = InputHelper::input("Nomor makanan (x untuk batal)");

            if($number == "x")
            {
                echo "Batal menghapus makanan." . PHP_EOL;
                return;
            }
            
            if(!is_numeric($number))
            {
                echo "Gagal menghapus makanan, nomor harus bilangan." . PHP_EOL;
                return;
            }

            $number = (int)$number;
            $foods = $this->foodService->getAllFood();
            
            if($number <= 0 || $number > count($foods))
            {
                echo "Gagal menghapus makanan nomor $number." . PHP_EOL;
                return;
            }

            $name = $foods[$number-1]->getName();
            $success = $this->foodService->removeFood($name);

            if($success)
            {
                echo "Sukses menghapus makanan nomor $number." . PHP_EOL;
            }else
            {
                echo "Gagal menghapus makanan nomor $number." . PHP_EOL;
            }
        }
    }