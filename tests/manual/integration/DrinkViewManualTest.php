<?php 

    require_once __DIR__ . "/../../../vendor/autoload.php";

    use Cafetaria\Config\Database;
    use Cafetaria\Repository\DrinkRepositoryImpl;
    use Cafetaria\Service\DrinkServiceImpl;
    use Cafetaria\View\DrinkView;


    function testViewShowDrink()
    {
        $connection = Database::getConnection();

        $drinkRepository = new DrinkRepositoryImpl($connection);
        $drinkService = new DrinkServiceImpl($drinkRepository);
        $drinkView = new DrinkView($drinkService);
        $drinkView->showDrink();
    }

    function testViewAddDrink()
    {
        $connection = Database::getConnection();

        $drinkRepository = new DrinkRepositoryImpl($connection);
        $drinkService = new DrinkServiceImpl($drinkRepository);
        $drinkView = new DrinkView($drinkService);
        $drinkView->addDrink();
    }

    testViewAddDrink();