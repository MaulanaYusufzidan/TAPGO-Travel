# QR Code Generator (vendored)

Source: https://github.com/kazuhikoarase/qrcode-generator
File: php/qrcode.php
Author: Kazuhiko Arase
License: MIT (see header inside qrcode.php)

Vendored directly into this project (instead of via Composer/Packagist)
because this build environment's network allowlist does not include
Packagist. No modifications were made to the original file.

Used by App\Services\QrCodeService to render booking QR codes as SVG
(PRD section 20: Digital Ticket — QR Code).
