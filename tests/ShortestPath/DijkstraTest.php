<?php

use Graphp\Algorithms\ShortestPath\Dijkstra;
use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;

class DijkstraTest extends BaseShortestPathTest
{
    protected function createAlg(Vertex $vertex)
    {
        return new Dijkstra($vertex);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testGraphParallelNegative()
    {
        // 1 -[10]-> 2
        // |         ^
        // \--[-1]---/
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $graph->createEdgeDirected($v1, $v2)->setWeight(10);
        $graph->createEdgeDirected($v1, $v2)->setWeight(-1);

        $alg = $this->createAlg($v1);

        $alg->getEdges();
    }
}
