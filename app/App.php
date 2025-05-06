<?php 

    require_once __DIR__ . "/../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;
    use Cafetaria\View\FoodView;

    $connection = Database::getConnection();

    $foodRepository = new FoodRepositoryImpl($connection);
    $foodService = new FoodServiceImpl($foodRepository);
    $foodView = new FoodView($foodService);
    
    echo "Cafetaria App" . PHP_EOL;