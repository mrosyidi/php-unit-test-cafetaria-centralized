<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\View\PaymentView;


    function testViewShowPayment()
    {
        $connection = Database::getConnection();

        $orderRepository = new OrderRepositoryImpl($connection);
        $paymentRepository = new PaymentRepositoryImpl($connection);

        $orderService = new OrderServiceImpl($orderRepository);
        $paymentService = new PaymentServiceImpl($paymentRepository);

        $paymentView = new PaymentView($orderService, $paymentService);
        $paymentView->showPayment();
    }

    testViewShowPayment();
