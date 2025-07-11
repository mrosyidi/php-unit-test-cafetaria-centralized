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

        public function testConstructWithNullPriceSetsSubTotalToNull()
        {
            $detail = new Detail(1, "Mie Ayam", null, 2);
            $this->assertNull($detail->getPrice());
            $this->assertNull($detail->getSubTotal());
        }

        public function testConstructWithNullQtySetsSubTotalToNull()
        {
            $detail = new Detail(1, "Es Oyen", 12000, null);
            $this->assertNull($detail->getQty());
            $this->assertNull($detail->getSubTotal());
        }

        public function testSetPriceUpdatesSubTotal()
        {
            $detail = new Detail(1, "Es Campur", 12000, 1);
            $detail->setPrice(15000);
            $this->assertEquals(15000, $detail->getPrice());
            $this->assertEquals(15000, $detail->getSubTotal());
        }

        public function testSetQtyUpdatesSubTotal()
        {
            $detail = new Detail(1, "Rawon", 12000, 1);
            $detail->setQty(3);
            $this->assertEquals(3, $detail->getQty());
            $this->assertEquals(36000, $detail->getSubTotal());
        }

        public function testSetPriceThrowsExceptionWhenNegative()
        {
            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Harga tidak boleh negatif.");

            $detail = new Detail();
            $detail->setPrice(-10000);
        }

        public function testSetQtyThrowsExceptionWhenZero()
        {
            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $detail = new Detail();
            $detail->setQty(0);
        }  

        public function testSetQtyThrowsExceptionWhenNegative()
        {
            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $detail = new Detail();
            $detail->setQty(-2);
        }
    }