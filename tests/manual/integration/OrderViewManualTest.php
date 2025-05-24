<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\View\OrderView;


    function testViewShowOrder()
    {
        $connection = Database::getConnection();

        $orderRepository = new OrderRepositoryImpl($connection);
        $orderService = new OrderServiceImpl($orderRepository);
        $orderView = new OrderView($orderService);
        $orderView->showOrder();
    }

    testViewShowOrder();