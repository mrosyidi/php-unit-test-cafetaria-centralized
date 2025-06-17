<?php 

    namespace Cafetaria;
    
    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Detail;
    use Cafetaria\Exception\InvalidDetailException;

    class DetailTest extends TestCase
    {
        public function testConstructorWithValidValues()
        {
            $detail = new Detail(1, "Es Campur", 12000, 2);
            $this->assertEquals(1, $detail->getCode());
            $this->assertEquals("Es Campur", $detail->getName());
            $this->assertEquals(12000, $detail->getPrice());
            $this->assertEquals(2, $detail->getQty());
            $this->assertEquals(24000, $detail->getSubTotal());
        }
    }