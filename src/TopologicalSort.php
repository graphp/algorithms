<?php

namespace Graphp\Algorithms;

use Graphp\Algorithms\BaseGraph;
use Fhaculty\Graph\Exception\UnexpectedValueException;
use Fhaculty\Graph\Vertex;
use Fhaculty\Graph\Set\Vertices;
use Fhaculty\Graph\Graph;

/**
 * topological sorting / order, also known as toposort / topsort, commonly used in resolving dependencies
 *
 * @author clue
 * @link http://en.wikipedia.org/wiki/Topological_sorting
 */
class TopologicalSort extends BaseGraph
{
    /**
     * run algorithm and return an ordered/sorted set of Vertices
     *
     * the topological sorting may be non-unique depending on your edges
     *
     * @return Vertices
     */
    public function getVertices()
    {
        $stack = array(); // visited nodes with unvisited children
        $visited = array();
        $output = array();

        /** @var Vertex $top */
        // TODO: avoid having to reverse all vertices multiple times
        // pick a node to examine next - assume there are isolated nodes
        foreach (\array_reverse($this->graph->getVertices()->getVector()) as $top) {
            $tid = $top->getId();
            if (!isset($visited[$tid])) { // don't examine if already found
                \array_push($stack, $top);
            }

            while ($stack) {
                /** @var Vertex $current */
                $node = \end($stack);
                $nid = $node->getId();

                $visited[$nid] = false; // temporary mark
                $found = false; // new children found during visit to this node

                // find the next node to visit
                /** @var Vertex $child */
                foreach (\array_reverse($node->getVerticesEdgeTo()->getVector()) as $child) {
                    $cid = $child->getId();
                    if (!isset($visited[$cid])) {
                        // found a new node - push it onto the stack
                        \array_push($stack, $child);
                        $found = true; // move onto the new node
                        break;
                    } else if ($visited[$cid] === false) {
                        // temporary mark => not a DAG
                        throw new UnexpectedValueException('Not a DAG');
                    }
                }

                if (!$found) {
                    \array_pop($stack); // no new children found - we're done with this node
                    $visited[$nid] = true; // mark as visited
                    \array_push($output, $node); // add to results
                }
            }
        }

        return new Vertices(\array_reverse($output, true));
    }
}
