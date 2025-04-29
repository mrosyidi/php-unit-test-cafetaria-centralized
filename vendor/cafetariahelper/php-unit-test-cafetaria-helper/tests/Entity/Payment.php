<?php 

    namespace Cafetaria\Helper\Entity;

    class Payment 
    {
        private int $code;
        public function __construct(int $code){ $this->code = $code; }
        public function getCode(): int { return $this->code; }
    }
