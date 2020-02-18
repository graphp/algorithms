<?php

use Graphp\Algorithms\MinimumCostFlow\CycleCanceling;
use Graphp\Graph\Graph;

class CycleCancellingTest extends BaseMcfTest
{
    protected function createAlgorithm(Graph $graph)
    {
        return new CycleCanceling($graph);
    }
}
