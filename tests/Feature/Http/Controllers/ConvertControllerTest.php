<?php

namespace Http\Controllers;

use App\Http\Controllers\ConvertController;
use PHPUnit\Framework\TestCase;

class ConvertControllerTest extends TestCase
{
    public function testConvertNumberToWords(){
        $convertController = new ConvertController();

        $input = 125.09;
        $expectedOutput = "SON CIENTO VEINTICINCO CON 09/100 SOLES";

        $output = $convertController->convertNumberToWords($input);

        $this->assertEquals($expectedOutput, $output);
    }
}
