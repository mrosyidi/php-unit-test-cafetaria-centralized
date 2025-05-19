<?php 

    namespace Cafetaria\Entity;

    use Cafetaria\Exception\InvalidOrderException;

    class Order
    {
        private ?int $code;
        private ?string $name;
        private ?int $price;
        private ?int $qty;
        private ?int $sub_total;

        public function __construct(?int $code = null, ?string $name = null, ?int $price = null, ?int $qty = null)
        {
            if($price !== null && $price < 0)
            {
                throw new InvalidOrderException("Harga tidak boleh negatif.");
            }

            if($qty === null || $qty <= 0)
            {
                throw new InvalidOrderException("Kuantitas harus lebih dari nol.");
            }

            if($price === null || $qty === null)
            {
                $this->sub_total = null;
            }else 
            {
                $this->sub_total = $price * $qty;
            }

            $this->code = $code;
            $this->name = $name;
            $this->price = $price;
            $this->qty = $qty;
        }

        public function getCode(): ?int
        {
            return $this->code;
        }

        public function setCode(?int $code): void
        {
            $this->code = $code;
        }

        public function getName(): ?string
        {
            return $this->name;
        }

        public function setName(?string $name): void
        {
            $this->name = $name;
        }

        public function getPrice(): ?int
        {
            return $this->price;
        }

        public function setPrice(?int $price): void
        {
            if($price !== null && $price < 0)
            {
                throw new InvalidOrderException("Harga tidak boleh negatif.");
            }

            $this->price = $price;
            $this->calculateSubTotal();
        }

        public function getQty(): ?int
        {
            return $this->qty;
        }

        public function setQty(?int $qty): void
        {
            if($qty !== null && $qty <= 0)
            {
                throw new InvalidOrderException("Kuantitas harus lebih dari nol.");
            }

            $this->qty = $qty;
            $this->calculateSubTotal();
        }

        public function getSubTotal(): ?int
        {
            return $this->sub_total;
        }

        private function calculateSubTotal(): void
        {
            if($this->price === null || $this->qty === null) 
            {
                $this->sub_total = null;
            }else 
            {
                $this->sub_total = $this->price * $this->qty;
            }
        }
    }