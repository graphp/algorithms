<?php

namespace Graphp\Algorithms;

use Fhaculty\Graph\Set\Vertices;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use SplObjectStorage;

/**
 * Tarjan's strongly connected components algorithm
 *
 * @link https://en.wikipedia.org/wiki/Tarjan%27s_strongly_connected_components_algorithm
 */
class StronglyConnectedComponents
{
    /** @var int */
    public $minSize = 1;

    /**
     * Get vertices adjacent to vertex
     *
     * @param Vertex $v
     * @return Vertex[]
     */
    protected function vertexAdjacent(Vertex $v)
    {
        return $v->getVerticesEdgeTo()->getVector();
    }

    /**
     * Find the strongly connected components of the given graph and return a set of Vertices which
     * refer to the original Vertexes
     *
     * @param Graph $graph
     * @return Vertices[]
     */
    public function stronglyConnectedVertices(Graph $graph)
    {
        /** @var SplObjectStorage $preorder int[] */
        $preorder = new SplObjectStorage();
        /** @var SplObjectStorage $lowlink int[] */
        $lowlink = new SplObjectStorage();
        /** @var SplObjectStorage $scc_found bool[] */
        $scc_found = new SplObjectStorage();
        /** @var Vertex[] $scc_queue */
        $scc_queue = array();
        /** @var int $i */
        $i = 0; // preorder counter
        /** @var Vertices[] $sccs */
        $sccs = array();

        foreach ($graph->getVertices() as $source) {
            if (!$scc_found->contains($source)) {
                $queue = array($source);
                while ($queue) {
                    /** @var Vertex $v */
                    $v = end($queue);
                    if (!$preorder->contains($v)) {
                        $i++;
                        $preorder[$v] = $i;
                    }
                    /** @var bool $done */
                    $done = true;
                    $v_nbrs = $this->vertexAdjacent($v);
                    foreach ($v_nbrs as $w) {
                        if (!$preorder->contains($w)) {
                            array_push($queue, $w);
                            $done = false;
                            break;
                        }
                    }
                    if ($done) {
                        $lowlink[$v] = $preorder[$v];
                        foreach ($v_nbrs as $w) {
                            if (!$scc_found->contains($w)) {
                                if ($preorder[$w] > $preorder[$v]) {
                                    $lowlink[$v] = min($lowlink[$v], $lowlink[$w]);
                                } else {
                                    $lowlink[$v] = min($lowlink[$v], $preorder[$w]);
                                }
                            }
                        }
                        array_pop($queue);
                        if ($lowlink[$v] === $preorder[$v]) {
                            $scc_found->attach($v);
                            /** @var Vertex[] $scc (Dictionary) */
                            $scc = array($v);
                            while ($scc_queue && $preorder[end($scc_queue)] > $preorder[$v]) {
                                /** @var Vertex $k */
                                $k = array_pop($scc_queue);
                                $scc_found->attach($k);
                                array_push($scc, $k);
                            }
                            array_push($sccs, new Vertices($scc));
                        } else {
                            array_push($scc_queue, $v);
                        }
                    }
                }
            }
        }

        $minSize = $this->minSize; // PHP < 5.4
        return array_filter($sccs, function (Vertices $vertices) use ($minSize) { return $vertices->count() >= $minSize; });
    }

    /**
     * Return a set of new Graphs each representing a discovered strongly connected connected component
     *
     * @param Graph $graph
     * @return Graph[]
     */
    public function stronglyConnectedGraph(Graph $graph)
    {
        $sccs = $this->stronglyConnectedVertices($graph);
        return array_map(function(Vertices $vertices) use ($graph) {
            return $graph->createGraphCloneVertices($vertices);
        }, $sccs);
    }
}