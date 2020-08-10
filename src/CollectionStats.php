<?php
declare(strict_types=1);

namespace Danydev\NPlusOneDetector;

class CollectionStats
{
    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var CollectionStatsId
     */
    private $id;

    public function __construct(CollectionStatsId $id)
    {
        $this->id = $id;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getId(): CollectionStatsId
    {
        return $this->id;
    }

    public function incrementCount(): void
    {
        $this->count += 1;
    }

    public function getOwnerClass(): string
    {
        return $this->id->getOwnerClass();
    }

    public function getCollectionElementsClass(): string
    {
        return $this->id->getCollectionElementsClass();
    }
}
