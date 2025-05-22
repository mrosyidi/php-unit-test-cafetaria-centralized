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

        public function testSetPriceUpdatesSubTotal()
        {
            $order = new Order(1, "Es Campur", 12000, 1);
            $order->setPrice(15000);
            $this->assertEquals(15000, $order->getPrice());
            $this->assertEquals(15000, $order->getSubTotal());
        }

        public function testSetQtyUpdatesSubTotal()
        {
            $order = new Order(1, "Rawon", 12000, 1);
            $order->setQty(3);
            $this->assertEquals(3, $order->getQty());
            $this->assertEquals(36000, $order->getSubTotal());
        }

        public function testSetPriceThrowsExceptionWhenNegative()
        {
            $this->expectException(InvalidOrderException::class);
            $this->expectExceptionMessage("Harga tidak boleh negatif.");

            $order = new Order();
            $order->setPrice(-10000);
        }

        public function testSetQtyThrowsExceptionWhenZero()
        {
            $this->expectException(InvalidOrderException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $order = new Order();
            $order->setQty(0);
        }   
    }