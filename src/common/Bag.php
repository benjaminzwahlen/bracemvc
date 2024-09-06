<?php
namespace benjaminzwahlen\bracemvc\common;
class Bag
{

    private array $local = array();

    public function __construct() {}

    public function isEmpty(): bool
    {
        return count($this->local) == 0;
    }

    public function get(string $paramName): ?string
    {
        if (!key_exists($paramName, $this->local))
            return null;
        return $this->local[$paramName];
    }

    public function has(string $paramName): bool
    {
        if (key_exists($paramName, $this->local))
            return true;
        return false;
    }

    public static function load(array $content): ?Bag
    {
        if ($content == null || !is_array($content))
            return new Bag();

        $bag = new Bag();
        foreach ($content as $key => $val) {
            $bag->local[$key] = $val;
        }
        return $bag;
    }

    public function getAll(): array
    {
        return $this->local;
    }
}
