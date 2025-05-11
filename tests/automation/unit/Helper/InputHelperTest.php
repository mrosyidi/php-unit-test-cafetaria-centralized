<?php 

    namespace Cafetaria\Helper;

    use PHPUnit\Framework\TestCase;

    class InputHelperTest extends TestCase
    {
        public function testInputWithValidStreamAndInput()
        {
            $input = "Muhammad Rosyidi";
            $stream = fopen('php://memory', 'r+');
            fwrite($stream, $input);
            rewind($stream);

            InputHelper::$inputStream = $stream;

            $this->expectOutputString("Nama: ");

            $result = InputHelper::input("Nama");

            $this->assertEquals($input, $result);
            fclose($stream);
        }

        public function testInputWithEmptyInfoShouldThrowException()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Info tidak boleh kosong.");
            InputHelper::input("");
        }

        public function testInputWithInvalidStreamShouldThrowException()
        {
            $invalidStream = tmpfile();
            fclose($invalidStream);

            InputHelper::$inputStream = $invalidStream;

            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage("Stream tidak valid.");
            InputHelper::input("Nama");
        }
    }