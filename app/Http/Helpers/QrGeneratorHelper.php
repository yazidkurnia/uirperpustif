<?php

namespace App\Http\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Str;

class QrGeneratorHelper
{
    public static function generateQr(string $transaction_uri): string
    {
        $qrCode = new QrCode(
            data: $transaction_uri,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $randomFileName = Str::random(12) . '.png';

        $filePath = public_path("assets/generated_qr/{$randomFileName}");
        $result->saveToFile($filePath);

        return 'assets/generated_qr/' . $randomFileName;
    }
}