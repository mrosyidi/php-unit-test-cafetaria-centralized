<?php 

    namespace Cafetaria\View;

    use Cafetaria\Service\DrinkService;
    use Cafetaria\Helper\InputHelper;
    use Cafetaria\Validator\DrinkValidator;
    use Cafetaria\View\DrinkListRenderer;

    class DrinkView 
    {
        private DrinkService $drinkService;

        public function __construct(DrinkService $drinkService)
        {
            $this->drinkService = $drinkService;
        }

        public function showDrink(): void 
        {
            while(true)
            {
                $drinks = $this->drinkService->getAllDrink();

                DrinkRenderer::render($drinks);

                echo "Menu Minuman" . PHP_EOL;
                echo "1. Tambah Minuman" . PHP_EOL;
                echo "2. Hapus Minuman" . PHP_EOL;
                echo "x. Kembali" . PHP_EOL;

                $pilihan = InputHelper::input("Pilih");

                if($pilihan == "1")
                {
                    $this->addDrink();
                }else if($pilihan == "2")
                {
                    $this->removeDrink();
                }else if($pilihan == "x")
                {
                    break;
                }else 
                {
                    echo "Pilihan tidak dimengerti" . PHP_EOL;
                }
            }
        }

        public function addDrink(): void 
        {
            $drinks = $this->drinkService->getAllDrink();

            echo "MENAMBAH MINUMAN" . PHP_EOL;

            $name = InputHelper::input("Nama minuman (x untuk batal)");

            if($name == "x")
            {
                echo "Batal menambah minuman." . PHP_EOL;
                return;
            }
            
            if(!DrinkValidator::isValidName($name))
            {
                echo "Gagal menambah minuman, nama tidak boleh kosong." . PHP_EOL;
                return;
            }

            if(DrinkValidator::isDuplicate($drinks, $name))
            {
                echo "Gagal menambah minuman, nama minuman sudah ada." . PHP_EOL;
                return;
            }

            $price = InputHelper::input("Harga minuman (x untuk batal)");

            if($price == "x")
            {
                echo "Batal menambah minuman." . PHP_EOL;
                return;
            }

            if(!is_numeric($price))
            {
                echo "Gagal menambah minuman, harga minuman harus bilangan." . PHP_EOL;
                return;
            }

            if (filter_var($price, FILTER_VALIDATE_INT) === false) 
            {
                echo "Gagal menambah minuman, harga minuman harus bilangan bulat." . PHP_EOL;
                return;
            }
            
            $price = (int)$price;  

            if($price <= 0)
            {
                echo "Gagal menambah minuman, harga minuman harus bilangan positif." . PHP_EOL;
                return;
            }

            $this->drinkService->addDrink($name, $price);

            echo "Sukses menambah minuman." . PHP_EOL;
        }

        public function removeDrink(): void 
        {
            echo "MENGHAPUS MINUMAN" . PHP_EOL;

            $number = InputHelper::input("Nomor minuman (x untuk batal)");

            if($number == "x")
            {
                echo "Batal menghapus minuman." . PHP_EOL;
                return;
            }
            
            if(!is_numeric($number))
            {
                echo "Gagal menghapus minuman, nomor harus bilangan." . PHP_EOL;
                return;
            }

            $number = (int)$number;
            $drinks = $this->drinkService->getAllDrink();
            
            if($number <= 0 || $number > count($drinks))
            {
                echo "Gagal menghapus minuman nomor $number." . PHP_EOL;
                return;
            }

            $name = $drinks[$number-1]->getName();
            $success = $this->drinkService->removeDrink($name);

            if($success)
            {
                echo "Sukses menghapus minuman nomor $number." . PHP_EOL;
            }else
            {
                echo "Gagal menghapus minuman nomor $number." . PHP_EOL;
            }
        }
    }