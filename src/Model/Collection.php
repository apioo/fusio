<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @template T
 */
class Collection implements \JsonSerializable
{
    /**
     * @var int|null
     */
    protected $totalResults;
    /**
     * @var array<T>|null
     */
    protected $entry;
    /**
     * @param int|null $totalResults
     */
    public function setTotalResults(?int $totalResults) : void
    {
        $this->totalResults = $totalResults;
    }
    /**
     * @return int|null
     */
    public function getTotalResults() : ?int
    {
        return $this->totalResults;
    }
    /**
     * @param array<T>|null $entry
     */
    public function setEntry(?array $entry) : void
    {
        $this->entry = $entry;
    }
    /**
     * @return array<T>|null
     */
    public function getEntry() : ?array
    {
        return $this->entry;
    }
    public function jsonSerialize()
    {
        return (object) array_filter(array('totalResults' => $this->totalResults, 'entry' => $this->entry), static function ($value) : bool {
            return $value !== null;
        });
    }
}
