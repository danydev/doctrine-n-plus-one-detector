<?php
declare(strict_types=1);

namespace Danydev\NPlusOneDetector;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\InitializePersistentCollectionEventArgs;
use Doctrine\ORM\Event\InitializeProxyEventArgs;
use Doctrine\ORM\Events;

class NPlusOneDetector
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $initializeEvents = [Events::initializeProxy, Events::initializePersistentCollection];

    /**
     * @var CollectionStats[]
     */
    private $collectionStatsById = [];

    /**
     * @var ProxyStats[]
     */
    private $proxyStatsByClass = [];

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Clear the information about n+1 detected as of now.
     */
    public function clear(): void
    {
        $this->collectionStatsById = [];
        $this->proxyStatsByClass = [];
    }

    /**
     * Start detecting n plus one queries.
     */
    public function start(): void
    {
        $this->entityManager->getEventManager()->addEventListener($this->initializeEvents, $this);
    }

    /**
     * Start detecting n plus one queries.
     */
    public function stop(): void
    {
        $this->entityManager->getEventManager()->removeEventListener($this->initializeEvents, $this);
    }

    /**
     * Get the n+1 detected as of now.
     *
     * @param int $threshold Specify a threshold to return info about n+1 that have at least the given number of queries.
     */
    public function getDetectedNPlusOne(int $threshold = 1): Result
    {
        $collectionStatsOverThreshold = [];
        foreach ($this->collectionStatsById as $stat) {
            if ($stat->getCount() > $threshold) {
                $collectionStatsOverThreshold[] = $stat;
            }
        }
        $proxyStatsByClassOverThreshold = [];
        foreach ($this->proxyStatsByClass as $stat) {
            if ($stat->getCount() > $threshold) {
                $proxyStatsByClassOverThreshold[] = $stat;
            }
        }

        return new Result($collectionStatsOverThreshold, $proxyStatsByClassOverThreshold);
    }

    /**
     * Handler for Events::initializeProxy
     */
    public function initializeProxy(InitializeProxyEventArgs $eventArgs): void
    {
        $proxy = $eventArgs->getProxy();
        $className = $this->entityManager->getClassMetadata(get_class($proxy))->getName();
        $proxyStats = $this->proxyStatsByClass[$className] ?? new ProxyStats($className);
        $proxyStats->incrementCount();
        $this->proxyStatsByClass[$className] = $proxyStats;
    }

    /**
     * Handler for Events::initializePersistentCollection
     */
    public function initializePersistentCollection(InitializePersistentCollectionEventArgs $eventArgs): void
    {
        $collection = $eventArgs->getCollection();
        $identifier = new CollectionStatsId(
            $this->entityManager->getClassMetadata(get_class($collection->getOwner()))->getName(),
            $collection->getTypeClass()->getName()
        );
        $collectionStats = $this->collectionStatsById[$identifier->getId()] ?? new CollectionStats($identifier);
        $collectionStats->incrementCount();
        $this->collectionStatsById[$identifier->getId()] = $collectionStats;
    }
}
