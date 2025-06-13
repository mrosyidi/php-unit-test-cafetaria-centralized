<?php 

    require_once __DIR__ . "/../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\View\FoodView;
    use Cafetaria\View\DrinkView;
    use Cafetaria\View\OrderView;
    use Cafetaria\View\PaymentView;
    use Cafetaria\Helper\InputHelper;

    $connection = Database::getConnection();

    $foodRepository = new FoodRepositoryImpl($connection);
    $drinkRepository = new DrinkRepositoryImpl($connection);
    $orderRepository = new OrderRepositoryImpl($connection);
    $paymentRepository = new PaymentRepositoryImpl($connection);

    $foodService = new FoodServiceImpl($foodRepository);
    $drinkService = new DrinkServiceImpl($drinkRepository);
    $orderService = new OrderServiceImpl($orderRepository);
    $paymentService = new PaymentServiceImpl($paymentRepository);

    $foodView = new FoodView($foodService);
    $drinkView = new DrinkView($drinkService);
    $orderView = new OrderView($foodService, $drinkService, $orderService, $paymentService);
    $paymentView = new PaymentView($orderService, $paymentService);
    
    echo "Cafetaria App" . PHP_EOL;

    while(true)
    {
        echo "MENU UTAMA" . PHP_EOL;
        echo "1. Daftar Makanan" . PHP_EOL;
        echo "2. Daftar Minuman" . PHP_EOL;
        echo "3. Pemesanan" . PHP_EOL;
        echo "4. Pembayaran" . PHP_EOL;
        echo "5. Detail" . PHP_EOL;
        echo "x. Keluar" . PHP_EOL;

        $pilihan = InputHelper::input("Pilih");

        if($pilihan == "1")
        {
            $foodView->showFood();
        }else if($pilihan == "2")
        {
            $drinkView->showDrink();
        }else if($pilihan == "3")
        {
            $orderView->showOrder();
        }else if($pilihan == "4")
        {
            $paymentView->showPayment();
        }else if($pilihan == "5")
        {
            
        }else if($pilihan == "x")
        {
            break;
        }else 
        {
            echo "Pilihan tidak dimengerti" . PHP_EOL;
        }
    }

    echo "Sampai Jumpa Lagi" . PHP_EOL;