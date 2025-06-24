<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Config\Database;
    use Cafetaria\Entity\Detail;
    use Cafetaria\Repository\DetailRepositoryImpl;
    use Cafetaria\Service\DetailServiceImpl;
    use Cafetaria\Exception\InvalidDetailException;

    class DetailServiceIntegrationTest extends TestCase 
    {
        private $detailRepository;
        private $detailService;

        public function setUp(): void 
        {
            $connection = Database::getConnection();
            $this->detailRepository = new DetailRepositoryImpl($connection);
            $this->detailService = new DetailServiceImpl($this->detailRepository);
            $this->detailRepository->removeAll();
        }

        public function testGetAllDetailWithData()
        {
            $detail = new Detail(1, "Jus Melon", 7000, 2, 14000);
            
            $this->detailRepository->save($detail);

            $details = $this->detailService->getAllDetail();

            $this->assertCount(1, $details);
            $this->assertEquals($detail->getCode(), $details[0]->getCode());
            $this->assertEquals($detail->getName(), $details[0]->getName());
            $this->assertEquals($detail->getPrice(), $details[0]->getPrice());
            $this->assertEquals($detail->getQty(), $details[0]->getQty());
            $this->assertEquals(14000, $details[0]->getSubTotal());
        }

        public function testGetAllDetailWithNoData()
        {
            $details = $this->detailService->getAllDetail();

            $this->assertEmpty($details);
        }

        public function testAddDetailSuccess()
        {
            $detail = new Detail(1, "Soto Daging", 12000, 1, 12000);
            $details[] = $detail;

            $this->detailService->addDetail($details);

            $details = $this->detailService->getAllDetail();

            $this->assertCount(1, $details);
            $this->assertEquals(1, $details[0]->getCode());
            $this->assertEquals("Soto Daging", $details[0]->getName());
            $this->assertEquals(12000, $details[0]->getPrice());
            $this->assertEquals(1, $details[0]->getQty());
            $this->assertEquals(12000, $details[0]->getSubTotal());
        }

        public function testAddDetailWithNegativePriceThrowsException()
        {
            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Harga tidak boleh negatif.");

            $detail = new Detail(1, "Gado-Gado", -12000, 2, 24000);

            $this->detailService->addDetail([$detail]); 
        }
    }