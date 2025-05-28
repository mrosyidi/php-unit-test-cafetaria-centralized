<?php 

    namespace Cafetaria\Repository;

    use \Cafetaria\Entity\Order;
    use \Cafetaria\Repository\OrderRepository;

    class OrderRepositoryImpl implements OrderRepository 
    {
        private \PDO $connection;

        public function __construct(\PDO $connection)
        {
            $this->connection = $connection;
        }

        public function findAll(): array 
        {
            $sql = "SELECT code,name,price,qty FROM orders";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $orders = [];

            foreach($rows as $row)
            {
                $order = new order();
                $order->setCode($row['code']);
                $order->setName($row['name']);
                $order->setPrice($row['price']);
                $order->setQty($row['qty']);
                $orders[] = $order;
            }

            return $orders;
        }

        public function save(Order $order): void 
        {
            $sql = "INSERT INTO orders(code,name,price,qty,sub_total) VALUES(?,?,?,?,?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$order->getCode(),$order->getName(),
            $order->getPrice(),$order->getQty(),$order->getSubTotal()]);
        }

        public function remove(int $code): void 
        {
            $sql = "DELETE FROM orders WHERE code=?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$code]);
        }

        public function removeAll(): void 
        {
            $sql = "DELETE FROM orders";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        }
    }