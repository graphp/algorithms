<?php

namespace Graphp\Algorithms\Property;

use Graphp\Algorithms\BaseGraph;

/**
 * Simple algorithms for working with Graph properties
 *
 * @link https://en.wikipedia.org/wiki/Graph_property
 */
class GraphProperty extends BaseGraph
{
    /**
     * checks whether this graph has no edges
     *
     * Also known as empty Graph. An empty Graph contains no edges, but can
     * possibly contain any number of isolated vertices.
     *
     * @return bool
     */
    public function isEdgeless()
    {
        return $this->graph->getEdges()->isEmpty();
    }

    /**
     * checks whether this graph is a null graph (no vertex - and thus no edges)
     *
     * Each Edge is incident to two Vertices, or in case of an loop Edge,
     * incident to the same Vertex twice. As such an Edge can not exist when
     * no Vertices exist. So if we check we have no Vertices, we can also be
     * sure that no Edges exist either.
     *
     * @return bool
     */
    public function isNull()
    {
        return $this->graph->getVertices()->isEmpty();
    }

    /**
     * checks whether this graph is trivial (one vertex and no edges)
     *
     * @return bool
     */
    public function isTrivial()
    {
        return ($this->graph->getEdges()->isEmpty() && \count($this->graph->getVertices()) === 1);
    }
    
    /**
     * checks whether this graph is acyclic (directed graph with no cycles)
     * using the Kahn algorithm
     * 
     * @return boolean
     * @link https://en.wikipedia.org/wiki/Directed_acyclic_graph
     */
    public function isAcyclic()
    {
        $vertices = $this->graph->getVertices();
        $nVertices = count($vertices);
        $visited = 0;
        $inDegree = array();
        $stack = array();

        foreach($vertices as $vert){
            $deg=count($vert->getEdgesIn());
            $inDegree[$vert->getId()]=$deg;
            if($deg==0){
                \array_push($stack,$vert);
            }
        }

        while(!(empty($stack))){
            $n = array_pop($stack);
            $visited++;
            foreach($n->getEdgesOut() as $e){
                $m = $e->getVertexEnd();
                $inDegree[$m->getId()]--;
                if($inDegree[$m->getId()]==0){
                    \array_push($stack,$m);
                }
            }
        }
        
        if($visited==$nVertices){
            return true;
        }
        else{
            return false;
        }
    }
}
