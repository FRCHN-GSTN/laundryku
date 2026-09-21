<?php

namespace App\Libraries;

/**
 * QRIS Generator - Convert Static QRIS to Dynamic
 * Based on EMVCo QR Code Specification
 */
class Qris
{
    /**
     * Convert static QRIS to dynamic with amount
     */
    public static function convertToDynamic(string $staticQris, int $amount): string
    {
        // Parse TLV elements
        $elements = self::parseTLV($staticQris);

        // Change tag 01 from 11 (static) to 12 (dynamic)
        $elements['01'] = '12';

        // Remove CRC (tag 63)
        unset($elements['63']);

        // Add amount (tag 54)
        $elements['54'] = self::formatAmount($amount);

        // Rebuild string
        $result = self::buildTLV($elements);

        // Calculate and append CRC16
        $crc = self::crc16($result);
        $result .= '63' . '04' . $crc;

        return $result;
    }

    /**
     * Parse TLV string to array
     */
    private static function parseTLV(string $data): array
    {
        $elements = [];
        $i = 0;
        $len = strlen($data);

        while ($i < $len - 4) { // -4 for CRC tag
            $tag = substr($data, $i, 2);
            $i += 2;

            $length = (int) substr($data, $i, 2);
            $i += 2;

            $value = substr($data, $i, $length);
            $i += $length;

            $elements[$tag] = $value;
        }

        return $elements;
    }

    /**
     * Build TLV string from array
     */
    private static function buildTLV(array $elements): string
    {
        $result = '';
        foreach ($elements as $tag => $value) {
            $length = strlen($value);
            $result .= $tag . str_pad($length, 2, '0', STR_PAD_LEFT) . $value;
        }
        return $result;
    }

    /**
     * Format amount for QRIS (no decimal, no separator)
     */
    private static function formatAmount(int $amount): string
    {
        return number_format($amount, 0, '', '');
    }

    /**
     * Calculate CRC16-CCITT
     */
    public static function crc16(string $data): string
    {
        $polynomial = 0x1021;
        $crc = 0xFFFF;

        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Generate QR code URL using goqr.me API
     */
    public static function getQrUrl(string $data): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($data);
    }
}
