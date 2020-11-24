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

    public function keys(){
        return $this->keysWithPrefix('');
    }

    public function keysWithPrefix($pre){
        $q = []; // queue
        $this->collect($this->executeGet($this->root, $pre, 0), $pre, $q);
        return $q;
    }

    protected function collect($x, $pre, &$q){
        if ($x == null){
            return;
        }

        if ($x->val != null){
            array_unshift($q, $pre);
        }

        for ($c = 0; $c < $this->R; $c++){
            $this->collect($x->next[$c], $pre + $c, $q);
        }
    }

    public function keysThatMatch($pat){
        $q = [];
        $this->matchCollect($this->root,'', $pat, $q);
        return $q;
    }

    public function matchCollect($x, $pre, $pat, &$q){
        $d = strlen($pre);

        if ($x == null){
            return;
        }

        if ($d == strlen($pat) && $x->val != null){
             array_unshift($q, $pre);
        }

        if ($d == strlen($pre)){
            return;
        }

        $next = $this->charAt($pat, $d);

        for ($c = 0; $c < $this->R; $c++){
            if ($next == '.' || $next == $c){
                $this->matchCollect($x->next[$c], $pre + $c, $pat, $q);
            }
        }
    }

    public function longestPrefixOf($s){
        $length = $this->search($this->root, $s, 0, 0);
        return substr($s, 0, $length);
    }

    private function search(Node $x, $s, $d, $length){
        if ($x == null){
            return $length;
        }

        if ($x->val != null){
            $length = $d;
        }

        if ($d == strlen($s)){
            return $length;
        }

        $c = $this->charAt($s, $d);
        return $this->search($x->next[$c], $s, $d + 1, $length);
    }

    public function delete($key){
        $this->root = $this->executeDelete($this->root, $key, 0);
    }

    /**
     * @param $x Node
     * @param $key string 要删除的 key
     * @param $d int 字符串 $key 的第几个字符
     * @return null
     */
    private function executeDelete(Node $x, $key, $d){
        if ($x == null){
            return null;
        }

        if ($d == strlen($key)){ // 已经找到键所对应的结点，只需将值设为 null 即可
            $x->val = null;
        } else {  // 没有找到键所对应的结点，继续找下一个字符对应的结点
            $c = $this->charAt($key, $d);
            $x->next[$c] = $this->executeDelete($x->next[$c], $key, $d + 1);
        }

        // 删除 key 对应结点的值后，如果它的链接均为空，那么需要删除这个结点。
        // 同理，如果删除 key 对应结点的值后，导致父结点的所有链接为空也要删除父结点
        if ($x->val != null){ // 当前结点不为空，不需要再删除父结点
            return $x;
        }

        // 如果 $x->val == null 并且他的所有链接都为空,就可以删除这个结点
        for ($c = 0; $c < $this->R; $c++){
            if ($x->next[$c] != null){
                return $x;
            }
        }

        return null;
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