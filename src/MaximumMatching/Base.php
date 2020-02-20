<?php

namespace Graphp\Algorithms\MaximumMatching;

use Graphp\Algorithms\BaseGraph;
use Graphp\Graph\Exception\UnexpectedValueException;
use Graphp\Graph\Graph;
use Graphp\Graph\Set\Edges;

abstract class Base extends BaseGraph
{
    /**
     * Get the count of edges that are in the match
     *
     * @return int
     * @throws UnexpectedValueException if graph is directed or is not bipartit
     * @uses Base::getEdges()
     */
    public function getNumberOfMatches()
    {
        return \count($this->getEdges());
    }

    /**
     * create new resulting graph with only edges from maximum matching
     *
     * @return Graph
     * @uses Base::getEdges()
     * @uses Graph::createGraphCloneEdges()
     */
    public function createGraph()
    {
        return $this->graph->createGraphCloneEdges($this->getEdges());
    }

    /**
     * create new resulting graph with minimum-cost flow on edges
     *
     * @return Edges
     */
    abstract public function getEdges();
}
