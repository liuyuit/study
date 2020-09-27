<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/27
 * Time: 11:34
 */


class TST
{
    protected $root; // 根结点

    public function push($key, $val){
        $this->root = $this->executedPush($this->root, $key, $val, 0);
    }

    /**
     * @param $x Node
     * @param $kye
     * @param $val
     * @param $d
     */
    public function executedPush($x, $kye, $val, $d){
        $c = $this->charAt($kye, $d);

        if ($x === null){
            $x = new Node();
            $x->c = $c;
        }

    }

    /**
     * 获取字符串相应位置的字符所对应的 ascii 码，字符串到达末尾了，就返回 -1
     * @param $string string
     * @param $d int
     * @return int|mixed
     */
    private  function charAt($string, $d){
        if ($d < strlen($string)){
            return ord($string[$d]);
        } else {
            return -1;
        }
    }
}


class Node{
    public $c; // char
    public $left; // 左子结点
    public $mid; //  中子结点
    public $right; // 右子结点
    public $val; // 值
}