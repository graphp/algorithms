<?php

namespace Graphp\Algorithms\Search;

use Fhaculty\Graph\Exception\DomainException;
use Fhaculty\Graph\Vertex;
use Fhaculty\Graph\Set\Vertices;

class DepthFirst extends Base
{
    const ITERATIVE = 1;
    const RECURSIVE = 2;

    /** @var int which algorithm to use */
    protected $mode;

    /**
     * DepthFirst constructor.
     * @param Vertex $vertex search start vertex
     * @param int    $mode   which algorithm to use
     */
    public function __construct(Vertex $vertex, $mode = self::ITERATIVE)
    {
        parent::__construct($vertex);
        $this->mode = $mode;
    }

    /**
     * set the algorithm to be used
     *
     * @see self::ITERATIVE
     * @see self::RECURSIVE
     *
     * @param int $mode which algorithm to use
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * calculates the recursive algorithm
     *
     * fills $this->visitedVertices
     *
     * @param Vertex $vertex
     * @param array  $visitedVertices
     */
    private function recursiveDepthFirstSearch(Vertex $vertex, array & $visitedVertices)
    {
        // If I didn't visited this vertex before
        if (!isset($visitedVertices[$vertex->getId()])) {
            // Add Vertex to already visited vertices
            $visitedVertices[$vertex->getId()] = $vertex;

            // Get next vertices
            $nextVertices = $vertex->getVerticesEdgeTo();

            foreach ($nextVertices as $nextVertix) {
                // recursive call for next vertices
                $this->recursiveDepthFirstSearch($nextVertix, $visitedVertices);
            }
        }
    }

    /**
     * calculates the iterative algorithm
     *
     * @param  Vertex $vertex
     * @return Vertices
     */
    private function iterativeDepthFirstSearch(Vertex $vertex)
    {
        $visited = array();
        $todo = array($vertex);
        while ($vertex = array_shift($todo)) {
            if (!isset($visited[$vertex->getId()])) {
                $visited[$vertex->getId()] = $vertex;

                foreach (array_reverse($this->getVerticesAdjacent($vertex)->getMap(), true) as $vid => $nextVertex) {
                    $todo[] = $nextVertex;
                }
            }
        }

        return new Vertices($visited);
    }

    /**
     * calculates a depth-first search
     *
     * @return Vertices
     */
    public function getVertices()
    {
        switch ($this->mode)
        {
            case self::ITERATIVE:
                return $this->iterativeDepthFirstSearch($this->vertex);

            case self::RECURSIVE:
                $visitedVertices = array();
                $this->recursiveDepthFirstSearch($this->vertex, $visitedVertices);
                return new Vertices($visitedVertices);

            default:
                throw new DomainException("Unknown algorithm type");
        }
    }
}
