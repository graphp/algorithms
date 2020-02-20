<?php

use Graphp\Algorithms\MinimumSpanningTree\Prim;
use Graphp\Graph\Vertex;

class PrimTest extends BaseMstTest
{
    protected function createAlg(Vertex $vertex)
    {
        return new Prim($vertex);
    }
}
