<?php

namespace Graphp\Tests\Algorithms\MinimumSpanningTree;

use Graphp\Algorithms\MinimumSpanningTree\Kruskal;
use Graphp\Graph\Graph;
use Graphp\Graph\Vertex;

class KruskalTest extends BaseMstTest
{
    protected function createAlg(Vertex $vertex)
    {
        return new Kruskal($vertex->getGraph());
    }

    public function testNullGraphIsNotConsideredToBeConnected()
    {
        $graph = new Graph();

        $alg = new Kruskal($graph);

        $this->setExpectedException('UnexpectedValueException');
        $alg->getEdges();
    }
}
