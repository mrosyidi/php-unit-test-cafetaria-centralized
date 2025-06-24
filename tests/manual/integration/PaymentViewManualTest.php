<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\OrderRepositoryImpl;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Repository\DetailRepositoryImpl;
    use Cafetaria\Service\OrderServiceImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\Service\DetailServiceImpl;
    use Cafetaria\View\PaymentView;


    function testViewShowPayment()
    {
        $connection = Database::getConnection();

        $orderRepository = new OrderRepositoryImpl($connection);
        $paymentRepository = new PaymentRepositoryImpl($connection);
        $detailRepository = new DetailRepositoryImpl($connection);

        $orderService = new OrderServiceImpl($orderRepository);
        $paymentService = new PaymentServiceImpl($paymentRepository);
        $detailService = new DetailServiceImpl($detailRepository);

        $paymentView = new PaymentView($orderService, $paymentService, $detailService);
        $paymentView->showPayment();
    }

    function testViewAddPayment()
    {
        $connection = Database::getConnection();

        $orderRepository = new OrderRepositoryImpl($connection);
        $paymentRepository = new PaymentRepositoryImpl($connection);
        $detailRepository = new DetailRepositoryImpl($connection);

        $orderService = new OrderServiceImpl($orderRepository);
        $paymentService = new PaymentServiceImpl($paymentRepository);
        $detailService = new DetailServiceImpl($detailRepository);

        $paymentView = new PaymentView($orderService, $paymentService, $detailService);
        $paymentView->addPayment();
    }

    testViewAddPayment();
