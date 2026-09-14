<?php

namespace App\Services;

/**
 * QrCodeService
 *
 * Membungkus library QR code pihak ketiga yang di-vendor langsung
 * (lihat app/Support/ThirdParty/qrcode-generator/) karena environment
 * build ini tidak punya akses Packagist untuk composer require paket
 * QR code (mis. endroid/qr-code, simplesoftwareio/simple-qrcode).
 *
 * PRD section 20 Digital Ticket: e-ticket harus menyertakan QR Code.
 */
class QrCodeService
{
    /**
     * Generate QR code sebagai markup SVG mentah (bisa langsung
     * di-embed di Blade tanpa perlu simpan file/gambar).
     */
    public function generateSvg(string $data, int $moduleSize = 4): string
    {
        $qr = \QRCode::getMinimumQRCode($data, QR_ERROR_CORRECT_LEVEL_M);
        $qr->make();

        // printSVG() dari library ini nge-print langsung (bukan return),
        // jadi kita tangkap outputnya lewat output buffering.
        ob_start();
        $qr->printSVG($moduleSize);

        return ob_get_clean();
    }
}
