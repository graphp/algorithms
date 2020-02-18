<?php

use Graphp\Algorithms\MinimumSpanningTree\Kruskal;
use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;

class KruskalTest extends BaseMstTest
{
    protected function createAlg(Vertex $vertex)
    {
        return new Kruskal($vertex->getGraph());
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testNullGraphIsNotConsideredToBeConnected()
    {
        $graph = new Graph();

        $alg = new Kruskal($graph);
        $alg->getEdges();
    }
}
