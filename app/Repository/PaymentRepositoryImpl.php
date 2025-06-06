<?php 

    namespace Cafetaria\Repository;

    use \Cafetaria\Entity\Payment;
    use \Cafetaria\Repository\PaymentRepository;

    class PaymentRepositoryImpl implements PaymentRepository
    {
        private \PDO $connection;

        public function __construct(\PDO $connection)
        {
            $this->connection = $connection;
        }

        public function findAll(): array 
        {
            $sql = "SELECT code,total,pay,changes FROM payments";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $payments = [];

            foreach($rows as $row)
            {
                $payment = new Payment();
                $payment->setCode($row['code']);
                $payment->setTotal($row['total']);
                $payment->setPay($row['pay']);
                $payments[] = $payment;
            }

            return $payments;
        }

        public function save(Payment $payment): void 
        {
            $sql = "INSERT INTO payments(code,total,pay,changes) VALUES(?,?,?,?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$payment->getCode(),$payment->getTotal(),$payment->getPay(),$payment->getChange()]);
        }

        public function removeAll(): void
        {

        }
    }