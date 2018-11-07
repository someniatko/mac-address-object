<?php declare(strict_types = 1);

namespace Tests;

use Someniatko\MacAddressObject\Mac;
use PHPUnit\Framework\TestCase;
use Someniatko\MacAddressObject\MacFormat;

final class MacTest extends TestCase
{
    /** @dataProvider validMacsProvider */
    public function testValidMacs(string $validMac) :void
    {
        $mac = new Mac($validMac);
        $this->assertEquals('00-11-22-33-44-55', $mac->formatWithDashes());
    }

    public function validMacsProvider() :iterable
    {
        yield 'dashes' => [ '00-11-22-33-44-55' ];
        yield 'colons' => [ '00:11:22:33:44:55' ];
        yield 'dots' => [ '0011.2233.4455' ];
        yield 'no delimiter' => [ '001122334455' ];
    }

    /** @dataProvider invalidMacsProvider */
    public function testInvalidMacTriggersInvalidArgumentException(string $invalidMac) :void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Mac($invalidMac);
    }

    public function invalidMacsProvider() :iterable
    {
        yield 'empty' => [ '' ];
        yield 'too long' => [ '00-11-22-33-44-55-66' ];
        yield 'too short' => [ '00-11-22-33-44' ];
        yield 'chars out of range' => [ '00-ii-22-33-44-55' ];
        yield 'mixed colons and dashes' => [ '00-11:22-33-44-55' ];
        yield 'invalid dots usage' => [ '00.11.22.33.44.55' ];
    }

    public function testToStringUsingDefaultFormat() :void
    {
        $mac = new Mac('00:11:22:33:44:55');
        $this->assertEquals('00-11-22-33-44-55', (string)$mac);
    }

    /** @dataProvider toStringFormatProvider */
    public function testToStringIfFormatGiven(MacFormat $format, string $expected) :void
    {
        $mac = new Mac('aa:bb:cc:dd:ee:ff', $format);
        $this->assertEquals($expected, (string)$mac);
    }

    public function toStringFormatProvider() :iterable
    {
        yield 'dashes' => [ MacFormat::DASHES(), 'aa-bb-cc-dd-ee-ff' ];
        yield 'colons' => [ MacFormat::COLONS(), 'aa:bb:cc:dd:ee:ff' ];
        yield 'dots' => [ MacFormat::DOTS(), 'aabb.ccdd.eeff' ];
        yield 'no delimiters' => [ MacFormat::NO_DELIMITERS(), 'aabbccddeeff' ];
    }

    public function testFormatNoDelimiters() :void
    {
        $this->assertEquals(
            '001122334455',
            (new Mac('00-11-22-33-44-55'))->formatNoDelimiters()
        );
    }

    public function testFormatWithDashes() :void
    {
        $this->assertEquals(
            '00-11-22-33-44-55',
            (new Mac('001122334455'))->formatWithDashes()
        );
    }

    public function testFormatWithColons() :void
    {
        $this->assertEquals(
            '00:11:22:33:44:55',
            (new Mac('0011.2233.4455'))->formatWithColons()
        );
    }

    public function testFormatWithDots() :void
    {
        $this->assertEquals(
            '0011.2233.4455',
            (new Mac('00-11-22-33-44-55'))->formatWithDots()
        );
    }
}
