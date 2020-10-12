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

    public function put($key, $val){
        $this->root = $this->executePut($this->root, $key, $val, 0);
    }

    /**
     * @param $x Node
     * @param $key
     * @param $val
     * @param $d
     * @return Node
     */
    public function executePut($x, $key, $val, $d){
        $c = $this->charAt($key, $d);

        if ($x === null){ // 进入到一个空结点，需要先创建结点。但这并不代表已经查找命中。还可能是到达目标结点之前的结点就为空
            $x = new Node();
            $x->c = $c;
        }

        if ($c < $x->c){ // 当前键的字母小于结点字母，进入左链接
            $x->left = $this->executePut($x->left, $key, $val, $d);
        } elseif ($c > $x->c){ // 当前键的字母大于结点字母，进入右链接
            $x->right = $this->executePut($x->right, $key, $val, $d);
        } elseif($d < strlen($key) -1){ // 当前键的字母等于结点字母，但还不是最后一个字母，进入中间连接
            $x->mid = $this->executePut($x->mid, $key, $val, $d + 1);
        } else { // 当前键的字母等于结点字母，并且是最后一个字母，查找已命中，将当前节点赋值即可
            $x->val = $val;
        }

        return $x;
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