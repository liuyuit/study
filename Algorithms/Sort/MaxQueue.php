<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
$maxQueue =  new MaxQueue();


/**
 * 优先队列
 */
class MaxQueue
{
    private $queue = array();
    private $num = 0;


    public function __construct()
    {
        $array = array(132,11, 125, 13, 8, 123, 12, 12346,14,345,34,64); //12
        $this->queue = $array;
        $this->num = count($array) - 1;
        $this->sort();

        $newArray = array(15,12,54,21154,0,786,453,0,45,7816,4,0,0); // 9 4
        foreach ($newArray as $value){
            if ($value == 0){
                if (!$this->isEmpty()){
                    echo $this->deleteMax();
                }
            } else{
                $this->insert($value);
            }
            echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp' . $this->max();
            echo '<br/>';
        }

        echo 'the MaxQueue Size:' . $this->size();
        echo '<br/>';

        $this->show();
    }

    /**
     * 构建二叉堆
     */
    public function sort(){
        for ($i = 1; $i <= $this->num; $i++){
            $this->sink($i);
        }

        for ($i = 1; $i <= $this->num; $i++){
            $this->sink($i);
        }
    }

    /**
     * 插入一个元素
     * @param $value
     */
    private function insert($value){
        $this->queue[++$this->num] = $value;
        $this->swim($this->num);
    }

    /**
     * 返回最大的元素
     */
    private function max(){
        return $this->queue[1];
    }

    /**
     * 删除并返回最大的元素
     */
    private function deleteMax(){
        $maxValue = $this->queue[1];
        $this->exch(1, $this->num);
        unset($this->queue[$this->num]);
        $this->num--;
        $this->sink(1);
        return $maxValue;
    }

    /**
     * 返回队列是否为空
     */
    private function isEmpty(){
        return $this->num == 0;
    }

    /**
     * 返回队列中元素个数
     */
    private function size(){
        return $this->num;
    }

    /**
     * 下沉操作
     * @param $key
     */
    private function sink($key){
        if (!isset($this->queue[2*$key])){
            return;
        }

        $maxChildIndex = $this->getMaxChildIndex($key);
        if ($this->queue[$key] < $this->queue[$maxChildIndex]){
            $this->exch($key, $maxChildIndex);
            $this->sink($maxChildIndex);
        }
    }

    private function getMaxChildIndex($key){
        if (!isset($this->queue[2 * $key])){
            return false;
        }

        if (!isset($this->queue[2 * $key +1])){
            return 2 * $key;
        }

        if ($this->less($this->queue[2 * $key], $this->queue[2 * $key +1])){
            return 2 * $key + 1;
        } else {
            return 2 * $key;
        }
    }

    /**
     * @param $key
     */
    private function swim($key){
        if ($key <= 1){
            return;
        }

        $paramIndex = (int)floor($key / 2);
        if ($this->less($this->queue[$paramIndex], $this->queue[$key])){
            $this->exch($paramIndex, $key);
            $this->swim($paramIndex);
        }
    }

    private function less($v, $w){
        return $v < $w;
    }

    private function exch( $i, $j){
        $temp = $this->queue[$i];
        $this->queue[$i] = $this->queue[$j];
        $this->queue[$j] = $temp;
    }

    public function show(){
        for ($i = 1; $i <= $this->num; $i++){
            echo $this->queue[$i];
            echo '<br/>';
        }
    }
}
