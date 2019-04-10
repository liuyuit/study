<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

$array = array(11, 125, 13, 123, 14, 15, 19, 23, 32, 32, 32);
$length = count($array);
$weightedUnionFind =  new WeightedUnionFind(132);
$i = 0;
while ($i < $length - 1){
    $p = $array[$i++];
    $q = $array[$i++];

    if ($weightedUnionFind->connected($p, $q)){
        continue;
    }

    $weightedUnionFind->union($p, $q);
    echo $p . '+' .$q;
    echo '<br/>';
}
echo 'count: ' . $weightedUnionFind->count();

class WeightedUnionFind
{
    private $id;        // 父链接数组（由触点索引）
    private $sz;        // （由触点索引的）各个根节点所对应的分量大小
    private $count;     // 连通分量的大小

    public function __construct(int $count){
        $this->count = $count;
        $this->id = array();
        for ($i = 0;$i < $count;$i++){
            $this->id[$i] = $i;
        }

        $this->sz = array();
        for ($i = 0;$i < $count;$i++){
            $this->sz[$i] = $i;
        }
    }

    public function count(){
        return $this->count;
    }

    public function connected(int $p, int $q){
        return $this->find($p) == $this->find($q);
    }

    private function find(int $p){
        while ($this->id[$p] != $p){
            $p = $this->id[$p];
        }

        return $p;
    }

    public function union(int $p, int $q){
        $pRoot = $this->find($p);
        $qRoot = $this->find($q);

        if ($pRoot == $qRoot){
            return;
        }

        if($this->sz[$qRoot] > $this->sz[$pRoot]){
            $this->id[$pRoot] = $qRoot;
            $this->sz[$qRoot] += $this->sz[$pRoot];
        } else {
            $this->id[$qRoot] = $pRoot;
            $this->sz[$pRoot] += $this->sz[$qRoot];
        }

        $this->id[$pRoot] = $qRoot;
        $this->count--;
    }
}






class UnionFind
{
    private $id;
    private $count;

    public function __construct(int $count){
        $this->count = $count;
        $this->id = array();
        for ($i = 0;$i < $count;$i++){
            $this->id[$i] = $i;
        }
    }

    public function count(){
        return $this->count;
    }

    public function connected(int $p, int $q){
        return $this->find($p) == $this->find($q);
    }

    private function find(int $p){
        while ($this->id[$p] != $p){
            $p = $this->id[$p];
        }

        return $p;
    }

    public function union(int $p, int $q){
        $pRoot = $this->find($p);
        $qRoot = $this->find($q);

        if ($pRoot == $qRoot){
            return;
        }

        $this->id[$pRoot] = $qRoot;
        $this->count--;
    }


    /*private function find(int $p){
        return $this->id[$p];
    }

    public function union(int $p, int $q){
        $pID = $this->find($p);
        $qID = $this->find($q);

        if ($pID == $qID){
            return;
        }

        foreach ($this->id as $key => $value){
            if ($value == $pID){
                $this->id[$key] = $qID;
            }
        }

        $this->count--;
    }*/
}
