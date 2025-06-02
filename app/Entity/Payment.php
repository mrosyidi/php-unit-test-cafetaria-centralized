<?php 

    namespace Cafetaria\Entity;

    use Cafetaria\Exception\InvalidPaymentException;

    class Payment 
    {
        private ?int $code;
        private ?int $total;
        private ?int $pay;
        private ?int $change;

        public function __construct(?int $code = null, ?int $total = 0, ?int $pay = null)
        {
            if($total < 0)
            {
                throw new InvalidPaymentException("Total tidak boleh negatif.");
            }

            if($pay < 0)
            {
                throw new InvalidPaymentException("Jumlah bayar tidak boleh negatif.");
            }

            if($pay < $total)
            {
                throw new InvalidPaymentException("Jumlah bayar kurang dari total.");
            }

            $this->code = $code;
            $this->total = $total;
            $this->pay = $pay;
            $this->change = $this->pay-$this->total;
        }

        public function setCode(int $code): void
        {
            $this->code = $code;
        }

        public function getCode(): int
        {
            return $this->code;
        }

        public function setTotal(int $total): void
        {
            if($total < 0)
            {
                throw new InvalidPaymentException("Total tidak boleh negatif.");
            }

            $this->total = $total;
            $this->calculateChange();
        }

        public function getTotal(): int
        {
            return $this->total;
        }

        public function setPay(int $pay): void
        {
            if($pay < 0)
            {
                throw new InvalidPaymentException("Jumlah bayar tidak boleh negatif.");
            }

            if($pay < $total)
            {
                throw new InvalidPaymentException("Jumlah bayar kurang dari total.");
            }

            $this->pay = $pay;
            $this->calculateChange();
        }

        public function getPay(): int
        {
            return $this->pay;
        }

        public function calculateChange(): void
        {
            $this->change = $this->pay-$this->total;
        }

        public function getChange(): int
        {
            return $this->change;
        }
    }