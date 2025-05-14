<?php 

    namespace Cafetaria\Repository;

    use Cafetaria\Entity\Drink;
    use Cafetaria\Repository\DrinkRepository;

    class DrinkRepositoryImpl implements DrinkRepository
    {
        private \PDO $connection;

        public function __construct(\PDO $connection)
        {
            $this->connection = $connection;
        }

        public function findAll(): array 
        {
            $sql = "SELECT name, price FROM drinks";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $drinks = [];

            foreach($rows as $row)
            {
                $drink = new Drink();
                $drink->setName($row['name']);
                $drink->setPrice($row['price']);
                $drinks[] = $drink;
            }

            return $drinks;
        }

        public function save(Drink $drink): void
        {
            $sql = "INSERT INTO drinks(name,price) VALUES(?,?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$drink->getName(),$drink->getPrice()]);
        }

        public function remove(string $name): bool
        {
            
        }

        public function removeAll(): void 
        {
            
        }
    }