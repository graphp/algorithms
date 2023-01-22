<?php

namespace Graphp\Tests\Algorithms\Property;

use Graphp\Algorithms\Property\GraphProperty;
use Graphp\Graph\Graph;
use Graphp\Tests\Algorithms\TestCase;

class PropertyGraphTest extends TestCase
{
    public function testEmptyIsEdgeless()
    {
        $graph = new Graph();

        $alg = new GraphProperty($graph);

        $this->assertTrue($alg->isNull());
        $this->assertTrue($alg->isEdgeless());
        $this->assertFalse($alg->isTrivial());
        $this->assertTrue($alg->isAcyclic());
    }

    public function testSingleVertexIsTrivial()
    {
        $graph = new Graph();
        $graph->createVertex(1);

        $alg = new GraphProperty($graph);

        $this->assertFalse($alg->isNull());
        $this->assertTrue($alg->isEdgeless());
        $this->assertTrue($alg->isTrivial());
        $this->assertTrue($alg->isAcyclic());
    }
    
    public function testUndirectedIsAcyclic()
    {
        $graph = new Graph();
        $graph->createVertex(1)->createEdge($graph->createVertex(2));
        
        $alg = new GraphProperty($graph);
        
        $this->assertFalse($alg->isNull());
        $this->assertFalse($alg->isEdgeless());
        $this->assertFalse($alg->isTrivial());
        $this->assertFalse($alg->isAcyclic());
    }
    
    public function testGraphSimpleIsAcyclic()
    {
        $graph = new Graph();
        $graph->createVertex(1)->createEdgeTo($graph->createVertex(2));
        
        $alg = new GraphProperty($graph);
        
        $this->assertFalse($alg->isNull());
        $this->assertFalse($alg->isEdgeless());
        $this->assertFalse($alg->isTrivial());
        $this->assertTrue($alg->isAcyclic());
    }
    
    public function testGraphWithCycleIsAcyclic()
    {
        $graph = new Graph();
        $vertexOne = $graph->createVertex(1);
        $vertexTwo = $graph->createVertex(2);
        $vertexThree = $graph->createVertex(3);
        $vertexOne->createEdgeTo($vertexTwo);
        $vertexTwo->createEdgeTo($vertexThree);
        $vertexThree->createEdgeTo($vertexOne);
        
        $alg = new GraphProperty($graph);
        
        $this->assertFalse($alg->isNull());
        $this->assertFalse($alg->isEdgeless());
        $this->assertFalse($alg->isTrivial());
        $this->assertFalse($alg->isAcyclic());
    }
}
