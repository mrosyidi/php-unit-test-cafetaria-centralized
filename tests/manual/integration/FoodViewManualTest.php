<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\View\FoodView;


    function testViewShowFood()
    {
        $connection = Database::getConnection();

        $foodRepository = new FoodRepositoryImpl($connection);
        $foodService = new FoodServiceImpl($foodRepository);
        $foodView = new FoodView($foodService);
        $foodView->showFood();
    }

    function testViewAddFood()
    {
        $connection = Database::getConnection();

        $foodRepository = new FoodRepositoryImpl($connection);
        $foodService = new FoodServiceImpl($foodRepository);
        $foodView = new FoodView($foodService);
        $foodView->addFood();
    }

    testViewAddFood();