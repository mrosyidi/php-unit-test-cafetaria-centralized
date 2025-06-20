<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\PaymentRepositoryImpl;
    use Cafetaria\Repository\DetailRepositoryImpl;
    use Cafetaria\Service\PaymentServiceImpl;
    use Cafetaria\Service\DetailServiceImpl;
    use Cafetaria\View\DetailView;


    function testViewShowDetail()
    {
        $connection = Database::getConnection();

        $paymentRepository = new PaymentRepositoryImpl($connection);
        $detailRepository = new DetailRepositoryimpl($connection);

        $paymentService = new PaymentServiceImpl($paymentRepository);
        $detailService = new DetailServiceImpl($detailRepository);

        $detailView = new DetailView($detailService, $paymentService);
        $detailView->showDetail();
    }

    testViewShowDetail();