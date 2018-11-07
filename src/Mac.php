<?php declare(strict_types = 1);

namespace Someniatko\MacAddressObject;

final class Mac
{
    private const methodsMap = [
        MacFormat::DASHES => 'formatWithDashes',
        MacFormat::COLONS => 'formatWithColons',
        MacFormat::DOTS => 'formatWithDots',
        MacFormat::NO_DELIMITERS => 'formatNoDelimiters',
    ];

    private $value;
    private $format;

    public function __construct(string $value, ?MacFormat $toStringFormat = null)
    {
        if (!self::validate($value)) {
            throw new \InvalidArgumentException($value. ' is not a valid MAC address');
        }

        $this->value = $value;
        $this->format = $toStringFormat ?? MacFormat::DASHES();
    }

    public function __toString() :string
    {
        return $this->{self::methodsMap[$this->format->getValue()]}($this->value);
    }

    public function formatNoDelimiters() :string
    {
        return preg_replace('/[^0-9a-f]/', '', strtolower($this->value));
    }

    public function formatWithDashes() :string
    {
        return $this->format('-', 2);
    }

    public function formatWithColons() :string
    {
        return $this->format(':', 2);
    }

    public function formatWithDots() :string
    {
        return $this->format('.', 4);
    }

    private function format(string $separator, int $chunkSize) :string
    {
        return implode($separator, str_split($this->formatNoDelimiters(), $chunkSize));
    }

    private static function validate(string $mac) :bool
    {
        return filter_var($mac, FILTER_VALIDATE_MAC) !== false
            || preg_match('/^[0-9a-f]{12}$/', $mac) === 1;
    }
}
