<?php 

    require_once __DIR__ . "/../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\FoodRepositoryImpl;

    $connection = Database::getConnection();

    $foodRepository = new FoodRepositoryImpl($connection);
    
    echo "Cafetaria App" . PHP_EOL;