<?php 

    namespace Cafetaria;

    use PHPUnit\Framework\TestCase;
    use Cafetaria\Entity\Detail;
    use Cafetaria\Repository\DetailRepository;
    use Cafetaria\Service\DetailServiceImpl;
    use Cafetaria\Exception\InvalidDetailException;

    class DetailServiceTest extends TestCase
    {
        private $detailRepository;
        private $detailService;

        protected function setUp(): void
        {
            $this->detailRepository = $this->createMock(DetailRepository::class);
            $this->detailService = new DetailServiceImpl($this->detailRepository);
        }

        public function testGetAllDetailWhenDataExist()
        {
            $details = [
                $this->createConfiguredMock(Detail::class, [
                    'getCode' => 1,
                    'getName' => 'Ayam Goreng',
                    'getPrice' => 12000,
                    'getQty' => 1,
                    'getSubTotal' => 12000
                ]),
                $this->createConfiguredMock(Detail::class, [
                    'getCode' => 1,
                    'getName' => 'Es Oyen',
                    'getPrice' => 12000,
                    'getQty' => 1,
                    'getSubTotal' => 12000
                ])
            ];

            $this->detailRepository->method('findAll')->willReturn($details);

            $details = $this->detailService->getAllDetail();

            $this->assertCount(2, $details);
            $this->assertEquals(1, $details[0]->getCode());
            $this->assertEquals('Ayam Goreng', $details[0]->getName());
            $this->assertEquals(12000, $details[0]->getPrice());
            $this->assertEquals(1, $details[0]->getQty());
            $this->assertEquals(12000, $details[0]->getSubTotal());
        }

        public function testGetAllDetailWhenNoDataExist()
        {
            $this->detailRepository->method('findAll')->willReturn([]);

            $details = $this->detailService->getAllDetail();

            $this->assertCount(0, $details);
        }

        public function testAddDetailSuccess()
        {
            $item = $this->createMock(Detail::class);
            $item->method('getCode')->willReturn(1);
            $item->method('getName')->willReturn("Ayam Panggang");
            $item->method('getPrice')->willReturn(12000);
            $item->method('getQty')->willReturn(2);
            $item->method('getSubTotal')->willReturn(24000);

            $items = [$item];

            $detail = new Detail(1, "Ayam Panggang", 12000, 2, 24000);

            $this->detailRepository->expects($this->once())
            ->method('save')->with($this->equalTo($detail));

            $this->detailService->addDetail($items);
        }

        public function testAddDetailWithNegativePriceThrowsException()
        {
            $item = $this->createMock(Detail::class);
            $item->method('getCode')->willReturn(1);
            $item->method('getName')->willReturn("Ayam Panggang");
            $item->method('getPrice')->willReturn(-12000);
            $item->method('getQty')->willReturn(2);
            $item->method('getSubTotal')->willReturn(24000);

            $items = [$item];

            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Harga tidak boleh negatif.");

            $this->detailService->addDetail($items);
        }

        public function testAddDetailWithZeroQtyThrowsException()
        {
            $item = $this->createMock(Detail::class);
            $item->method('getCode')->willReturn(1);
            $item->method('getName')->willReturn("Ayam Panggang");
            $item->method('getPrice')->willReturn(12000);
            $item->method('getQty')->willReturn(0);
            $item->method('getSubTotal')->willReturn(24000);

            $items = [$item];

            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $this->detailService->addDetail($items);
        }

        public function testAddDetailWithNegativeQtyThrowsException()
        {
            $item = $this->createMock(Detail::class);
            $item->method('getCode')->willReturn(1);
            $item->method('getName')->willReturn("Ayam Panggang");
            $item->method('getPrice')->willReturn(12000);
            $item->method('getQty')->willReturn(-2);
            $item->method('getSubTotal')->willReturn(24000);

            $items = [$item];

            $this->expectException(InvalidDetailException::class);
            $this->expectExceptionMessage("Kuantitas harus lebih dari nol.");

            $this->detailService->addDetail($items);
        }
    }