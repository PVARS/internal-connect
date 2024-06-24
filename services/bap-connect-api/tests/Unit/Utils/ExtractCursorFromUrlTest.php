<?php

namespace tests\Unit\Utils;

use App\Utils\Util;
use Tests\TestCase;

class ExtractCursorFromUrlTest extends TestCase
{
    public function test_extract_cursor_from_url_with_null_url(): void
    {
        $actual = Util::extractCursorFromUrl(null);

        $this->assertNull($actual);
    }

    public function test_extract_cursor_from_url_with_url_empty_url(): void
    {
        $actual = Util::extractCursorFromUrl('');

        $this->assertNull($actual);
    }

    public function test_extract_cursor_from_url_with_url_has_cursor(): void
    {
        $expected = 'abcd1234';
        $actual = Util::extractCursorFromUrl('https://example.com?cursor='.$expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_extract_cursor_from_url_with_url_dont_have_cursor(): void
    {
        $actual = Util::extractCursorFromUrl('https://example.com');

        $this->assertNull($actual);
    }

    public function test_extract_cursor_from_url_with_empty_url_cursor(): void
    {
        $actual = Util::extractCursorFromUrl('https://example.com?cursor');

        $this->assertEmpty($actual);
    }

    public function test_extract_cursor_from_url_with_invalid_url(): void
    {
        $invalidUrls = [
            'http://',
            'http://example',
            '://example.com',
            'http:/example.com',
            'http//example.com',
            'http://example..com',
            'http://example .com',
            'http://-example.com',
            'http://example,com',
            'http://example@com',
            'http://example_com',
            'http://.example.com',
            'http://example..com',
        ];

        foreach ($invalidUrls as $url) {
            $actual = Util::extractCursorFromUrl($url);
            $this->assertNull($actual);
        }
    }
}
