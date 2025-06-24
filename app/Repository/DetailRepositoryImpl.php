<?php 

    namespace Cafetaria\Repository;

    use Cafetaria\Entity\Detail;

    class DetailRepositoryImpl implements DetailRepository
    {
        private \PDO $connection;

        public function __construct(\PDO $connection)
        {
            $this->connection = $connection;
        }

        public function findAll(): array
        {
            $sql = "SELECT code, name, price, qty FROM details";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $details = [];

            foreach($rows as $row)
            {
                $detail = new Detail();
                $detail->setCode($row['code']);
                $detail->setName($row['name']);
                $detail->setPrice($row['price']);
                $detail->setQty($row['qty']);
                $details[] = $detail;
            }

            return $details;
        }

        public function save(Detail $detail): void
        {
            $sql = "INSERT INTO details(code,name,price,qty,sub_total) VALUES(?,?,?,?,?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$detail->getCode(),$detail->getName(),
            $detail->getPrice(),$detail->getQty(),$detail->getSubTotal()]);
        }

        public function removeAll(): void 
        {
            $sql = "DELETE FROM details";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        }
    }