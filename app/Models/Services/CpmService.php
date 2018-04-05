<?php

namespace App\Models\Services;


    class Vex{
        private $data;
        private $headLink;
        private $enterLimit = 0;

        public function __construct($data, $headLink = NULL){
            $this->data = $data;
            $this->headLink = $headLink;
        }
        public function enterLimitAdd($n = 1){
            $this->enterLimit += $n;
        }

        public function getEnterLimit(){
            return $this->enterLimit;
        }

        public function getData(){
            return $this->data;
        }

        public function getHeadLink(){
            return $this->headLink;
        }

        public function setHeadLink(&$link){
            $this->headLink = $link;
        }
    }

    class Arc{
        private $key;
        private $weight;
        private $next;

        public function __construct($key, $weight = NULL, $next = NULL){
            $this->key= $key;
            $this->next = $next;
            $this->weight= $weight;
        }

        public function getWeight(){
            return $this->weight;
        }

        public function getKey(){
            return $this->key;
        }

        public function getNext(){
            return $this->next;
        }

        public function setNext($next){
            $this->next = $next;
        }
    }

    class CpmService
    {
        private $vexsData;//array('a', 'b');
        private $vexs;//
        private $arcData;//array('ab'=>'3')
        private $excuteDfsResult;
        private $hasList;
        private $queue;
        private $direct;
        private $weight;

        public function __construct($vexsData, $arcData, $direct = 0, $weight = 0)
        {
            $this->vexsData = $vexsData;
            $this->arcData = $arcData;
            $this->direct = $direct;
            $this->weight = $weight;

            $this->createHeadList();
            $this->createArc();
        }

        private function createHeadList()
        {
            foreach ($this->vexsData as $value) {
                $this->vexs[] = new Vex($value);
            }
        }

        private function createArc()
        {
            switch ($this->weight) {
                case '0':
                    $this->createNoWeightArc();
                    break;
                case '1':
                    $this->createWeightArc();
                    break;
            }
        }

        private function createWeightArc()
        {
            foreach ($this->arcData as $key => $value) {
                $edgeNode = str_split($key);
                $this->createConnect($edgeNode[0], $edgeNode[1], $value);

                if (!$this->direct) {
                    $this->createConnect($edgeNode[1], $edgeNode[0], $value);
                }
            }

        }

        private function createNoWeightArc()
        {
            foreach ($this->arcData as $value) {
                $str = str_split($value);

                $this->createConnect($str[0], $str[1]);
                if (!$this->direct) {
                    $this->createConnect($str[1], $str[0]);
                }
            }
        }

        private function createConnect($first, $last, $weight = NULL)
        {
            $lastTemp =$this->vexs[$this->getVexByValue($last)];
            $lastTemp->enterLimitAdd(1);

            $firstNode =$this->vexs[$this->getVexByValue($first)];
            $lastNode = new Arc($this->getVexByValue($last), $weight);

            $lastNode->setNext($firstNode->getHeadLink());
            $firstNode->setHeadLink($lastNode);
        }

        // calculate the critical path
        public function criticalPath()
        {
            $etvs = array();//early start
            $ltvs = array();//late start
            $stacks = array();
            $result = array();

            foreach ($this->vexs as $value) {
                $etvs[$value->getData()] = 0;
            }

            $this->expandSeq($etvs, $stacks);
            $temp = end($etvs);

            foreach ($this->vexs as $value) {
                $ltvs[$value->getData()] = $temp;
            }

            while ($top = array_pop($stacks)) {
                $pre = $top->getHeadLink();

                while ($pre) {
                    $tempNode = $this->vexs[$pre->getKey()];
                    if ($ltvs[$top->getData()] > $ltvs[$tempNode->getData()] - $pre->getWeight()) {
                        $ltvs[$top->getData()] = $ltvs[$tempNode->getData()] - $pre->getWeight();
                    }

                    $pre = $pre->getNext();
                }
            }

            foreach ($this->vexs as $value) {
                if ($ltvs[$value->getData()] == $etvs[$value->getData()]) {
                    $result[] = $value->getData();
                }
            }
            print_r($etvs);
            print_r($ltvs);
            print_r($stacks);
            return $result;
        }

        public function expandSeq(& $etv = FALSE, & $stacks)
        {
            $zeroEnter = array();
            $result = array();

            foreach ($this->vexs as $value) {
                if ($value->getEnterLimit() == 0) {
                    $zeroEnter[] = $value;
                }
            }

            while (!empty($zeroEnter)) {
                $node = array_shift($zeroEnter);
                $result[] = $node->getData();
                array_push($stacks, $node);
                $pre = $node->getHeadLink();

                while ($pre) {
                    $temp =& $this->vexs[$pre->getKey()];
                    $temp->enterLimitAdd(-1);

                    if ($etv) {
                        if ($etv[$temp->getData()] < $etv[$node->getData()] + $pre->getWeight()) {
                            $etv[$temp->getData()] = $etv[$node->getData()] + $pre->getWeight();
                        }
                    }

                    if ($temp->getEnterLimit() == 0) {
                        $zeroEnter[] = $temp;
                    }

                    $pre = $pre->getNext();
                }
            }

            return $result;
        }

        private function getVexByValue($value)
        {
            foreach ($this->vexs as $k => $v) {
                if ($v->getData() == $value) {
                    return $k;
                }
            }
        }

        public function bfs()
        {
            $this->hasList = array();
            $this->queue = array();

            foreach ($this->vexs as $key => $value) {
                if (!in_array($value->getData(), $this->hasList)) {
                    $this->hasList[] = $value->getData();
                    $this->queue[] = $value->getHeadLink();

                    while (!empty($this->queue)) {
                        $node = array_shift($this->queue);

                        while ($node) {
                            if (!in_array($this->vexs[$node->getKey()]->getData(), $this->hasList)) {
                                $info = $this->vexs[$node->getKey()];
                                $this->hasList[] = $info->getData();
                                $this->queue[] = $info->getHeadLink();
                            }

                            $node = $node->getNext();
                        }
                    }
                }
            }

            return implode($this->hasList);
        }

        public function dfs()
        {
            $this->hasList = array();

            foreach ($this->vexs as $key => $value) {
                if (!in_array($key, $this->hasList)) {
                    $this->hasList[] = $key;
                    $this->excuteDfs($this->vexs[$key]->getHeadLink());
                }
            }

            foreach ($this->hasList as $key => $value) {
                $this->hasList[$key] = $this->vexs[$value]->getData();
            }

            return implode($this->hasList);
        }

        private function excuteDfs($arc)
        {
            if (!$arc || in_array($arc->getKey(), $this->hasList)) {
                return false;
            }

            $this->hasList[] = $arc->getKey();
            $next = $this->vexs[$arc->getKey()]->getHeadLink();

            while ($next) {
                $this->excuteDfs($next);
                $next = $next->getNext();
            }
        }

        public function getVexs()
        {
            return $this->vexs;
        }

    }
