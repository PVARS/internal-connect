<?php

namespace App\Utils;

class Util
{
    /**
     * Encrypts data.
     *
     * @param  string  $payload  Data need encrypt
     * @param  string  $encryptMethod  Encrypt method
     * @return string|null the encrypted string on success or null on failure.
     */
    public static function opensslEncrypt(
        string $payload,
        string $encryptMethod = 'AES-256-CBC'
    ): ?string {
        if (!env('ENCRYPT_SECRET_KEY') || !env('SECRET_IV')) {
            return null;
        }
        $key = hash('sha256', env('ENCRYPT_SECRET_KEY'));
        $iv = substr(hash('sha256', env('SECRET_IV')), 0, 16);

        $dataEncrypt = openssl_encrypt($payload, $encryptMethod, $key, 0, $iv);
        if (!$dataEncrypt) {
            return null;
        }

        return base64_encode($dataEncrypt);
    }

    /**
     * Decrypts data.
     *
     * @param  string  $payload  Data need encrypt
     * @param  string  $encryptMethod  Encrypt method
     * @return string|null the encrypted string on success or null on failure.
     */
    public static function opensslDecrypt(
        string $payload,
        string $encryptMethod = 'AES-256-CBC'
    ): ?string {
        if (!env('ENCRYPT_SECRET_KEY') || !env('SECRET_IV')) {
            return null;
        }
        $key = hash('sha256', env('ENCRYPT_SECRET_KEY'));
        $iv = substr(hash('sha256', env('SECRET_IV')), 0, 16);
        $dataEncrypt = openssl_decrypt(base64_decode($payload, true), $encryptMethod, $key, 0, $iv);

        return !$dataEncrypt ? null : $dataEncrypt;
    }

    /**
     * Extracts the 'cursor' parameter from a given URL's query string.
     *
     * This function validates that the provided URL is well-formed and contains a query string.
     * If valid, it parses the query string and returns the value of the 'cursor' parameter.
     * If the URL is invalid or does not contain the 'cursor' parameter, it returns null.
     *
     * @param  string|null  $url  The URL from which to extract the 'cursor' parameter.
     * @return string|null The value of the 'cursor' parameter, or null if not found or invalid URL.
     */
    public static function extractCursorFromUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL) ||
            !preg_match(Constants::REGEX_VALID_TLD, parse_url($url, PHP_URL_HOST))) {
            return null;
        }

        $parsedUrl = parse_url($url);
        $queryString = $parsedUrl['query'] ?? null;
        if (!$queryString) {
            return $queryString;
        }

        $queryParams = [];
        parse_str($queryString, $queryParams);

        return $queryParams['cursor'] ?? null;
    }
}
