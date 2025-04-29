<?php 

    namespace Cafetaria\Helper\Entity;

    class Order 
    {
        private int $code;
        private int $subTotal;
        
        public function __construct(int $code, int $subTotal)
        { 
            $this->code = $code; 
            $this->subTotal = $subTotal;
        }

        public function getCode(): int { return $this->code; }
        public function getSubTotal(): int { return $this->subTotal; }
    }
