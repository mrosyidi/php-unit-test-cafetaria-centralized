<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;

    class InputHelperTest extends TestCase
    {
        public function testInputDisplaysPromptAndReturnsProvidedValue()
        {
            $info = "Nama";
            $providedInput = "Muhammad Rosyidi";

            $this->expectOutputString("Nama: Muhammad Rosyidi\n");

            $result = InputHelper::input($info, $providedInput);
            
            $this->assertEquals($providedInput, $result);
        }

        public function testInputReadsFromProvidedStream()
        {
            $simulatedInput = "Muhammad Rosyidi";
            $inputStream = fopen('php://memory', 'r+');
            fwrite($inputStream, $simulatedInput);
            rewind($inputStream);

            $this->expectOutputString("Nama: ");

            $result = InputHelper::input("Nama", null, $inputStream);
            fclose($inputStream);

            $this->assertEquals("Muhammad Rosyidi", $result);
        }

        public function testInputThrowsExceptionWhenInfoIsEmpty()
        {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage("Info tidak boleh kosong.");

            InputHelper::input("", "Muhammad Rosyidi");
        }

        public function testInputThrowsExceptionWhenStreamIsInvalid()
        {
            $this->expectOutputString("Nama: ");
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage("Masukan stream yang disediakan tidak valid.");

            InputHelper::input("Nama", null, "invalid_stream");
        }

        public function testInputThrowsExceptionWhenReadingFromClosedStream()
        {
            $inputStream = fopen('php://memory', 'r+');

            $this->expectOutputString("Nama: ");
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage("Gagal membaca masukan.");

            InputHelper::input("Nama", null, $inputStream);
            fclose($inputStream);
        }
    }