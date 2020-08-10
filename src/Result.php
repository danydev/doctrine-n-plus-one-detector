<?php
declare(strict_types=1);

namespace Danydev\NPlusOneDetector;

class Result
{
    /**
     * @var CollectionStats[]
     */
    private $collectionStats;

    /**
     * @var ProxyStats[]
     */
    private $proxyStats;

    public function __construct(array $collectionStats, array $proxyStats)
    {
        $this->collectionStats = $collectionStats;
        $this->proxyStats = $proxyStats;
    }

    /**
     * @return CollectionStats[]
     */
    public function getCollectionStats(): array
    {
        return $this->collectionStats;
    }

    /**
     * @return ProxyStats[]
     */
    public function getProxyStats(): array
    {
        return $this->proxyStats;
    }
}
