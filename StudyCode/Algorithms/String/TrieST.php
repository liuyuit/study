<?php
// 在原文的 java 代码中字符和数字相加会自动将字符转化为相应的 ascii 码，在这里需要用 ord 函数来将字符转化为 ascii 码，
// ord('c');
//echo phpinfo();exit;


TrieSTExample();
function TrieSTExample(){
    $a = [
        'she',
        'sells',
        'seashells',
        'by',
        'the',
        'shells',
        'she',
        'shells',
        'are',
        'surely',
        'seashells',
    ];
//    TrieST::sort($a);
    print_r($a);
}

class TrieST
{
    public $R = 256;   // 基数
    public $root; // 单词查找树的根结点


    public function get($key){
        $x = $this->executeGet($this->root, $key, 0);
        if ($x == null){
            return null;
        }
        return $x->val;
    }

    /**
     * @param $x Node
     * @param $key
     * @param $d
     * @return Node|null
     */
    private function executeGet($x, $key, $d){
        if ($x == null){
            return null;
        }

        if ($d == strlen($key)){
            return $x;
        }

        $c = $this->charAt($key, $d);
        return $this->executeGet($x->next[$c], $key, $d);
    }

    public function put($key, $val){
        $this->root = $this->executePut($this->root, $key, $val, 0);
    }

    private function executePut($x, $key, $val, $d){
        if ($x == null){
            $x = new Node();
        }

        if ($d == strlen($key)){
            $x->val = $val;
            return $x;
        }

        $c = $this->charAt($key, $d);
        $x->next[$c] = $this->executePut($x->next[$c], $key, $val, $d + 1);
        return $x;
    }

    public function size(){
        return $this->executeSize($this->root);
    }

    /**
     * 统计一个单词查找数的所有键的数量
     * @param $x Node
     * @return int
     */
    protected function executeSize($x){
        if ($x == null){
            return 0;
        }

        $cnt = 0;
        if ($x->val != null){
            $cnt++; // 有值的结点代表一个键值对
        }

        for ($c = 0; $c < $this->R; $c++){ // 每一个结点都有 R 条链接
            $cnt += $this->executeSize($x->next[$c]); // 把每一个子结点当作一棵子树的根结点，递归地查找子树的键值对数量
        }

        return $cnt;
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
    public $val;
    public $next;
}