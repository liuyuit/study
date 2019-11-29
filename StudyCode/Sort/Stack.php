<?php
namespace Sort;

$whiteList = array(11, 12, 13, 123, 0, 123, 0, 346, 0);
$stack =  new Stack();
foreach ($whiteList as $value){
    if ($value != 0){
        $stack->push($value);
    }else{
        if (!$stack->isEmpty()){
            echo $stack->pop();
            echo "<br>";
        }
    }
}

echo $stack->size();


/**
 * 下压堆栈（链表实现）
 */
class Stack
{
    private $first;
    private $num;

    public function isEmpty(){
        return $this->first == null;
    }

    public function size(){
        return $this->num;
    }

    public function push($item){
        $oldFirst = $this->first;
        $first = new \stdClass();
        $first->item = $item;
        $first->next = $oldFirst;
        $this->first = $first;
        $this->num++;
    }

    public function pop(){
        if ($this->num == 0){
            return false;
        }
        $item = $this->first->item;
        $this->first = $this->first->next;
        $this->num--;
        return $item;
    }
}
