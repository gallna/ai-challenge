<?php
namespace Challenge;

class RecordsHeap extends \SplMinHeap
{
    /**
     * {@inheritDoc}
     */
    public function compare($a, $b)
    {
        return $b->getDistance() <=> $a->getDistance();
    }
}
