<?php
declare(strict_types=1);

namespace Danydev\NPlusOneDetector;

class CollectionStatsId
{
    /**
     * @var string
     */
    private $ownerClass;

    /**
     * @var string
     */
    private $collectionElementsClass;

    public function __construct(string $ownerClass, string $collectionElementsClass)
    {
        $this->ownerClass = $ownerClass;
        $this->collectionElementsClass = $collectionElementsClass;
    }

    public function getId(): string
    {
        return $this->ownerClass . '_' . $this->collectionElementsClass;
    }

    public function getOwnerClass(): string
    {
        return $this->ownerClass;
    }

    public function getCollectionElementsClass(): string
    {
        return $this->collectionElementsClass;
    }
}
