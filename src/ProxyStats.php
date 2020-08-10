<?php
declare(strict_types=1);

namespace Danydev\NPlusOneDetector;

class ProxyStats
{
    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var string
     */
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function incrementCount(): void
    {
        $this->count += 1;
    }
}
