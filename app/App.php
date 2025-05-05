<?php 

    require_once __DIR__ . "/../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;
    use Cafetaria\Service\FoodServiceImpl;

    $connection = Database::getConnection();

    $foodRepository = new FoodRepositoryImpl($connection);
    $foodService = new FoodServiceImpl($foodRepository);
    
    echo "Cafetaria App" . PHP_EOL;