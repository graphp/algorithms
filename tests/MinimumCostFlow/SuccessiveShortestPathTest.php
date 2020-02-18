<?php

use Graphp\Algorithms\MinimumCostFlow\SuccessiveShortestPath;
use Graphp\Graph\Graph;

class SuccessiveShortestPathTest extends BaseMcfTest
{
    protected function createAlgorithm(Graph $graph)
    {
        return new SuccessiveShortestPath($graph);
    }
}
