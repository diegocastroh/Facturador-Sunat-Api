<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConvertController extends Controller
{
    //
    public function convertNumberToWords($number)
    {
        $units = [
            "",
            "UN",
            "DOS",
            "TRES",
            "CUATRO",
            "CINCO",
            "SEIS",
            "SIETE",
            "OCHO",
            "NUEVE",
            "DIEZ",
            "ONCE",
            "DOCE",
            "TRECE",
            "CATORCE",
            "QUINCE",
            "DIECISEIS",
            "DIECISIETE",
            "DIECIOCHO",
            "DIECINUEVE",
            "VEINTE",
            "VEINTIUNO",
            "VEINTIDOS",
            "VEINTITRES",
            "VEINTICUATRO",
            "VEINTICINCO",
            "VEINTISEIS",
            "VEINTISIETE",
            "VEINTIOCHO",
            "VEINTINUEVE"
        ];

        $tens = [
            "",
            "",
            "",
            "TREINTA",
            "CUARENTA",
            "CINCUENTA",
            "SESENTA",
            "SETENTA",
            "OCHENTA",
            "NOVENTA"
        ];

        $hundreds = [
            "",
            "CIENTO",
            "DOSCIENTOS",
            "TRESCIENTOS",
            "CUATROCIENTOS",
            "QUINIENTOS",
            "SEISCIENTOS",
            "SETECIENTOS",
            "OCHOCIENTOS",
            "NOVECIENTOS"
        ];

        $number = number_format($number, 2, '.', '');

        list($integer, $decimal) = explode('.', $number);

        $integerPart = intval($integer);
        $decimalPart = intval($decimal);

        $integerWords = [];

        if ($integerPart == 0) {
            $integerWords[] = "CERO";
        }

        if ($integerPart >= 100) {
            $hundredsDigit = floor($integerPart / 100);
            $integerPart %= 100;
            $integerWords[] = $hundreds[$hundredsDigit];
        }

        if ($integerPart >= 30) {
            $tensDigit = floor($integerPart / 10);
            $integerPart %= 10;
            $integerWords[] = $tens[$tensDigit];
        }

        if ($integerPart > 0) {
            $integerWords[] = $units[$integerPart];
        }

        $decimalnum = "";

        if ($decimalPart<10){
            $decimalnum = "0".$decimalPart;
        }else{
            $decimalnum = "".$decimalPart;
        }

        $result = implode(" ", $integerWords) . " CON $decimalnum/100 SOLES";
        return "SON " . $result;
    }
}
