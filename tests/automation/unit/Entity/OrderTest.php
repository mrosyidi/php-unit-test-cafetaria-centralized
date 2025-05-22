<?php 

    namespace Cafetaria;
    
    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Order;
    use Cafetaria\Exception\InvalidOrderException;

    class OrderTest extends TestCase
    {
        public function testConstructorWithValidValues()
        {
            $order = new Order(1, "Es Campur", 12000, 2);
            $this->assertEquals(1, $order->getCode());
            $this->assertEquals("Es Campur", $order->getName());
            $this->assertEquals(12000, $order->getPrice());
            $this->assertEquals(2, $order->getQty());
            $this->assertEquals(24000, $order->getSubTotal());
        }

        public function testConstructWithNullPriceSetsSubTotalToNull()
        {
            $order = new Order(1, "Mie Ayam", null, 2);
            $this->assertNull($order->getPrice());
            $this->assertNull($order->getSubTotal());
        }

        public function testConstructWithNullQtySetsSubTotalToNull()
        {
            $order = new Order(1, "Es Oyen", 12000, null);
            $this->assertNull($order->getQty());
            $this->assertNull($order->getSubTotal());
        }
    }