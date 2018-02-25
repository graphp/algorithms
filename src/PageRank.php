<?php

namespace Graphp\Algorithms;

use Fhaculty\Graph\Exception\InvalidArgumentException;
use Fhaculty\Graph\Exception\UnderflowException;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;

class PageRank
{
    const DEFAULT_DAMPING_FACTOR = 0.85;
    const DEFAULT_MAX_DISTANCE = 0.000001;
    const DEFAULT_MAX_ROUNDS = 1000;
    const INITIAL_PAGE_RANK_SUM = 1;

    /** @var Graph */
    protected $graph;

    /** @var float */
    protected $dampingFactor;

    /** @var float */
    protected $maxDistance;

    /** @var int */
    protected $maxRounds;

    /** @var float[] */
    protected $rank;

    /** @var callable */
    protected $callback;

    /**
     * PageRank constructor.
     * @param Graph $graph
     * @param null $dampingFactor
     * @param null $maxDistance
     * @param null $maxRounds
     */
    public function __construct(Graph $graph, $dampingFactor = null, $maxDistance = null, $maxRounds = null)
    {
        $this->graph = $graph;
        $this->setDampingFactor($dampingFactor ?: static::DEFAULT_DAMPING_FACTOR);
        $this->setMaxDistance($maxDistance ?: static::DEFAULT_MAX_DISTANCE);
        $this->setMaxRounds($maxRounds ?: static::DEFAULT_MAX_ROUNDS);
    }

    /**
     * @param float $dampingFactor
     */
    public function setDampingFactor($dampingFactor)
    {
        if (!is_numeric($dampingFactor)) {
            throw new InvalidArgumentException("Invalid damping factor (expected float, got " . gettype($dampingFactor) . ")");
        }
        $this->dampingFactor = (float)$dampingFactor;
    }

    /**
     * @return float
     */
    public function getDampingFactor()
    {
        return $this->dampingFactor;
    }

    /**
     * @param float $maxDistance
     */
    public function setMaxDistance($maxDistance)
    {
        if (!is_numeric($maxDistance)) {
            throw new InvalidArgumentException("Invalid max distance (expected float, got " . gettype($maxDistance) . ")");
        }
        if ($maxDistance < 0) {
            throw new InvalidArgumentException("Invalid max distance (expected value >= 0)");
        }
        $this->maxDistance = (float)$maxDistance;
    }

    /**
     * @return float
     */
    public function getMaxDistance()
    {
        return $this->maxDistance;
    }

    /**
     * @param int $maxRounds
     */
    public function setMaxRounds($maxRounds)
    {
        if (!is_int($maxRounds)) {
            throw new InvalidArgumentException("Invalid max rounds (expected int, got " . gettype($maxRounds) . ")");
        }
        $this->maxRounds = (float)$maxRounds;
    }

    /**
     * @return int
     */
    public function getMaxRounds()
    {
        return $this->maxRounds;
    }

    /**
     * @param Vertex $vertex
     * @return float
     */
    public function getPageRank(Vertex $vertex)
    {
        return $this->rank[$vertex->getId()];
    }

    /**
     * @param Vertex $vertex
     * @return float
     */
    protected function calculatePageRank(Vertex $vertex)
    {
        $sum = 0;
        /** @var Vertex $in */
        foreach ($vertex->getVerticesEdgeFrom() as $in) {
            $sum += $this->rank[$in->getId()]/$in->getEdgesOut()->count();
        }
        return ((1 - $this->dampingFactor) / count($this->rank)) + $this->dampingFactor * $sum;
    }

    /**
     * @throws UnderflowException
     */
    public function convergePageRank()
    {
        $round = 0;
        $vertices = $this->graph->getVertices()->getMap();
        $this->rank = array_fill_keys(array_keys($vertices), static::INITIAL_PAGE_RANK_SUM / count($vertices));
        do {
            $newRank = array();
            $maxDistance = 0;
            foreach ($vertices as $vertexId => $vertex) {
                $oldRank = $this->rank[$vertexId];
                $newRank[$vertexId] = $this->calculatePageRank($vertex);
                $distance = abs($oldRank - $newRank[$vertexId]);
                if ($distance > $maxDistance) {
                    $maxDistance = $distance;
                }
            }
            foreach ($vertices as $vertexId => $vertex) {
                $this->rank[$vertexId] = $newRank[$vertexId];
            }
            $hasConverged = !($maxDistance == 0 || $maxDistance > $this->maxDistance);
            $this->postRoundHook(++$round);
        } while ($round < $this->maxRounds && !$hasConverged);
        if (!$hasConverged) {
            throw new UnderflowException("PageRank did not converge after {$this->maxRounds} rounds");
        }
    }

    /**
     * @param callable $callback
     */
    public function setPostRoundCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param int $round
     */
    protected function postRoundHook($round)
    {
        if ($this->callback) {
            $fn = $this->callback;
            $fn($round);
        }
    }
}