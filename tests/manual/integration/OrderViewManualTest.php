<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\Service\DrinkServiceImpl; 
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\View\OrderView;


    function testViewShowOrder()
    {
        $connection = Database::getConnection();

        $foodRepository = new FoodRepositoryImpl($connection);
        $drinkRepository = new DrinkRepositoryImpl($connection);
        $orderRepository = new OrderRepositoryImpl($connection);
        $paymentRepository = new PaymentRepositoryImpl($connection);

        $foodService = new FoodServiceImpl($foodRepository);
        $drinkService = new DrinkServiceImpl($drinkRepository);
        $orderService = new OrderServiceImpl($orderRepository);
        $paymentService = new PaymentServiceImpl($paymentRepository);

        $orderView = new OrderView($foodService, $drinkService, $orderService, $paymentService);
        $orderView->showOrder();
    }

    function testViewAddOrder()
    {
        
        $connection = Database::getConnection();

        $foodRepository = new FoodRepositoryImpl($connection);
        $drinkRepository = new DrinkRepositoryImpl($connection);
        $orderRepository = new OrderRepositoryImpl($connection);
        $paymentRepository = new PaymentRepositoryImpl($connection);

        $foodService = new FoodServiceImpl($foodRepository);
        $drinkService = new DrinkServiceImpl($drinkRepository);
        $orderService = new OrderServiceImpl($orderRepository);
        $paymentService = new PaymentServiceImpl($paymentRepository);

        $orderView = new OrderView($foodService, $drinkService, $orderService, $paymentService);
        $orderView->addOrder(2,true);
    }

    testViewShowOrder();