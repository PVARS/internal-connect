<?php

namespace tests\Unit\Utils;

use App\Utils\Util;
use Tests\TestCase;

class OpenSSLTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['ENCRYPT_SECRET_KEY'] = 'my_secret_key';
        $_ENV['SECRET_IV'] = 'my_secret_iv';
    }

    public function test_openssl_encrypt_not_empty_payload(): void
    {
        $data = Util::opensslEncrypt('TestPayload');
        $isBase64 = $this->isBase64($data);

        $this->assertNotNull($data);
        $this->assertTrue($isBase64);
    }

    public function test_openssl_encrypt_empty_payload(): void
    {
        $data = Util::opensslEncrypt('');
        $isBase64 = $this->isBase64($data);

        $this->assertNotNull($data);
        $this->assertTrue($isBase64);
    }

    public function test_openssl_encrypt_different_method(): void
    {
        $payload = 'TestPayload';
        $encryptMethod = 'AES-128-CBC';
        $encrypted = Util::opensslEncrypt($payload, $encryptMethod);

        $this->assertNotNull($encrypted);
        $this->assertTrue($this->isBase64($encrypted));
    }

    public function test_openssl_encrypt_invalid_method(): void
    {
        $payload = 'TestPayload';
        $encryptMethod = 'INVALID_METHOD';
        $encrypted = Util::opensslEncrypt($payload, $encryptMethod);
        $this->assertNull($encrypted);
    }

    public function test_openssl_encrypt_no_env_environments(): void
    {
        $_ENV['ENCRYPT_SECRET_KEY'] = '';
        $_ENV['SECRET_IV'] = '';

        $payload = 'TestPayload';
        $encrypted = Util::opensslEncrypt($payload);
        $this->assertNull($encrypted);
    }

    public function test_openssl_decrypt_not_empty_payload(): void
    {
        $expected = 'TestPayload';
        $actual = Util::opensslDecrypt(Util::opensslEncrypt($expected));

        $this->assertEquals($expected, $actual);
    }

    public function test_openssl_decrypt_empty_payload(): void
    {
        $expected = '';
        $actual = Util::opensslDecrypt(Util::opensslEncrypt($expected));

        $this->assertEquals($expected, $actual);
    }

    public function test_openssl_decrypt_different_method(): void
    {
        $payload = 'TestPayload';
        $encryptMethod = 'AES-128-CBC';
        $expected = Util::opensslEncrypt($payload, $encryptMethod);
        $actual = Util::opensslDecrypt(Util::opensslEncrypt($expected));

        $this->assertEquals($expected, $actual);
    }

    public function test_openssl_decrypt_invalid_method(): void
    {
        $payload = 'TestPayload';
        $encryptMethod = 'INVALID_METHOD';
        $encrypted = Util::opensslEncrypt($payload);
        $actual = Util::opensslDecrypt($encrypted, $encryptMethod);

        $this->assertNotNull($encrypted);
        $this->assertNull($actual);
    }

    public function test_openssl_decrypt_no_env_environments(): void
    {
        $payload = 'TestPayload';
        $encrypted = Util::opensslEncrypt($payload);

        $_ENV['ENCRYPT_SECRET_KEY'] = '';
        $_ENV['SECRET_IV'] = '';

        $actual = Util::opensslDecrypt($encrypted);

        $this->assertNull($actual);
    }

    /**
     * Validate base 64.
     *
     * @param  string  $string  base64
     * @return bool bool True if the string is base64, False otherwise.
     */
    private function isBase64(string $string): bool
    {
        $decoded = base64_decode($string, true);

        return $decoded && base64_encode($decoded) === $string;
    }
}
