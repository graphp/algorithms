<?php

namespace Graphp\Algorithms;

use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Set\Vertices;
use Fhaculty\Graph\Vertex;

class SimpleCycles
{
    /** @var Vertex[] */
    protected $blockedSet;

    /** @var Vertex[][] */
    protected $blockedMap;

    /** @var Vertex[] */
    protected $stack;

    /** @var Vertices[] */
    protected $cycles;

    /**
     * Remove node from blocked set and recursively remove all dependent nodes from blocked set
     * @param Vertex $v Node to unblock
     */
    protected function unblock(Vertex $v)
    {
        unset($this->blockedSet[$v->getId()]);
        if (isset($this->blockedMap[$v->getId()])) {
            foreach ($this->blockedMap[$v->getId()] as $w) {
                if (isset($this->blockedSet[$w->getId()])) {
                    $this->unblock($w);
                }
            }
            unset($this->blockedMap[$v->getId()]);
        }
    }

    /**
     * Recursive node visit procedure
     * @param Vertex $r Root node
     * @param Vertex $v Visited node
     * @return bool Found cycle
     */
    protected function visit(Vertex $r, Vertex $v)
    {
        array_push($this->stack, $v);
        $this->blockedSet[$v->getId()] = $v;
        $found = false;
        // Examine adjacent nodes
        foreach ($v->getVerticesEdgeTo() as $w) {
            if ($w->getId() === $r->getId()) {
                // Adjacent node == start node .'. found a cycle
                $found = true;
                // Cycle is whatever is on the stack
                $this->cycles[] = new Vertices($this->stack);
            } else if (!isset($this->blockedSet[$w->getId()])) {
                // Only visit adjacent node if not blocked
                $found = $this->visit($r, $w) || $found;
            }
        }
        if ($found) {
            // If cycle found, block node and all nodes mapped to it
            $this->unblock($v);
        } else {
            // Otherwise add node to all adjacent nodes' blocked map
            foreach ($v->getVerticesEdgeTo() as $w) {
                if (!isset($this->blockedMap[$w->getId()])) {
                    $this->blockedMap[$w->getId()] = array();
                }
                $this->blockedMap[$w->getId()][$v->getId()] = $v;
            }
        }
        array_pop($this->stack);
        return $found;
    }

    /**
     * @param Graph $g
     */
    protected function findCycles(Graph $g)
    {
        $this->blockedSet = array();
        $this->blockedMap = array();
        $this->stack = array();
        foreach ($g->getVertices() as $v) {
            $this->visit($v, $v);
            $v->destroy();
        }
    }

    /**
     * @param Graph $graph
     * @return array|Vertices[]
     */
    public function getSimpleCycles(Graph $graph)
    {
        $this->cycles = array();
        $components = new StronglyConnectedComponents();
        foreach ($components->stronglyConnectedGraph($graph) as $component) {
            $this->findCycles($component);
        }
        return $this->cycles;
    }
}