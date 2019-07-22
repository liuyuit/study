<?php
namespace Sort;

//exampleQueue();
use stdClass;

function exampleQueue(){
    $whiteList = array(11, 12, 13, 123,0,0, 12, 123, 0, 346, 0,0);
    $queue =  new Queue();
    foreach ($whiteList as $value){
        if ($value != 0){
            $queue->enQueue($value);
        }else{
            if (!$queue->isEmpty()){
                echo $queue->deQueue();
                echo "<br>";
            }
        }
    }

    echo 'the Queue length :' . $queue->size();
}


/**
 * 先进先出队列（链表实现）
 */
class Queue
{
    private $first;
    private $last;
    private $num;

    public function isEmpty(){
        return $this->first == null;
    }

    public function size(){
        return $this->num;
    }

    public function enQueue($item){
        $oldLast = $this->last;
        $this->last = new stdClass();
        $this->last->item = $item;
        $this->last->next = null;

        if ($this->first == null){
            $this->first= $this->last;
        } else {
            $oldLast->next = $this->last;
        }

        $this->num++;
    }

    public function deQueue(){
        if ($this->num == 0){
            return false;
        }

        $item = $this->first->item;
        $this->first = $this->first->next;
        $this->num--;
        return $item;
    }
}
