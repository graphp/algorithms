<?php

namespace Graphp\Algorithms;

use Fhaculty\Graph\Exception\InvalidArgumentException;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Set\Vertices;
use Fhaculty\Graph\Vertex;

/**
 * Finds the Strongly Connected Components in a Directed Graph, a graph component being said to be strongly connected
 * if every vertex is reachable from every other vertex.
 *
 * More information here:
 * @see https://en.wikipedia.org/wiki/Tarjan%27s_strongly_connected_components_algorithm
 *
 * This code was inspired by:
 * @see http://github.com/Trismegiste/Mondrian/blob/master/Graph/Tarjan.php and
 * @see https://code.google.com/p/jbpt/source/browse/trunk/jbpt-core/src/main/java/org/jbpt/algo/graph/StronglyConnectedComponents.java
 */
class Tarjan
{
    /** @var Graph  */
    private $graph;

    /** @var \SplObjectStorage  */
    private $indexMap;

    /** @var \SplObjectStorage  */
    private $lowLinkMap;

    /** @var Vertex[] */
    private $stack;

    /** @var int */
    private $index;

    /** @var Vertices[] */
    private $partition;

    /**
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
        $this->indexMap = new \SplObjectStorage();
        $this->lowLinkMap = new \SplObjectStorage();
    }

    /**
     * Get the strongly connected components of this digraph by the Tarjan algorithm.
     *
     * @throws InvalidArgumentException For undirected graph argument.
     * @return Vertices[] Array of Strongly Connected components.
     */
    public function getStronglyConnectedVertices()
    {
        // check is directed
        $directed = new Directed($this->graph);
        if ($directed->hasUndirected()) {
            throw new InvalidArgumentException('Graph shall be directed');
        }

        $this->stack = array();
        $this->index = 0;
        $this->partition = array();

        foreach ($this->graph->getVertices()->getVector() as $vertex) {
            if (! isset($this->indexMap[$vertex])) {
                $this->recursiveStrongConnect($vertex);
            }
        }

        return $this->partition;
    }

    /**
     * Find recursively connected vertices to a vertex and update strongly connected component
     * partition with it.
     *
     * @param Vertex $v
     */
    private function recursiveStrongConnect(Vertex $v)
    {
        $this->indexMap[$v] = $this->index;
        $this->lowLinkMap[$v] = $this->index;
        $this->index++;
        array_push($this->stack, $v);

        // Consider successors of v
        foreach ($v->getVerticesEdgeTo() as $w) {
            if (! isset($this->indexMap[$w]) ) {
                // Successor w has not yet been visited; recurse on it
                $this->recursiveStrongConnect($w);
                $this->lowLinkMap[$v] = min(array($this->lowLinkMap[$v], $this->lowLinkMap[$w]));
            } elseif (in_array($w, $this->stack)) {
                // Successor w is in stack S and hence in the current SCC
                $this->lowLinkMap[$v] = min(array($this->lowLinkMap[$v], $this->indexMap[$w]));
            }
        }
        // If v is a root node, pop the stack and generate an SCC
        if ($this->lowLinkMap[$v] === $this->indexMap[$v]) {
            $scc = array();
            do {
                $w = array_pop($this->stack);
                array_push($scc, $w);
            } while ($w !== $v);

            if (count($scc)) {
                $this->partition[] = new Vertices($scc);
            }
        }
    }
}
