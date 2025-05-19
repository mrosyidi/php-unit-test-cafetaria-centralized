<?php 

    namespace Cafetaria\Entity;

    use Cafetaria\Exception\InvalidDrinkException;

    class Drink 
    {
        private ?string $name;
        private ?int $price;

        public function __construct(?string $name = null, ?int $price = null)
        {
            if($name !== null && trim($name) === '')
            {
                throw new InvalidDrinkException("Nama tidak boleh kosong.");
            }

            if($price !== null && $price <= 0)
            {
                throw new InvalidDrinkException("Harga harus lebih dari nol.");
            }

            $this->name = $name;
            $this->price = $price;
        }

        public function getName(): ?string 
        {
            return $this->name;
        }

        public function setName(?string $name): void 
        {
            if($name !== null && trim($name) === '')
            {
                throw new InvalidDrinkException("Nama tidak boleh kosong.");
            }

            $this->name = $name;
        }

        public function getPrice(): ?int 
        {
            return $this->price;
        }

        public function setPrice(?int $price): void 
        {
            if($price !== null && $price <= 0)
            {
                throw new InvalidDrinkException("Harga harus lebih dari nol.");
            }

            $this->price = $price;
        }
    }