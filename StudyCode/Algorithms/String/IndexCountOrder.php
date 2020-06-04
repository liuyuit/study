<?php

highOrderExample();

function highOrderExample(){
    /**
     * 学生名字 => 组号
     * @var array $students
     */
    $students = [
       'Anderson' => 2,
       'Brown' => 3,
       'Davis' => 1,
       'Garcia' => 3,
       'Harris' => 2,
       'jackson' => 3,
       'Johnson' => 3,
       'Jones' => 1,
       'Martin' => 1,
       'Moore' => 3,
   ];

    new IndexCountOrder($students);
}

class IndexCountOrder
{
    public function __construct($students)
    {
        $count = [];

        /**
         * 频率统计
         * @var int $r 学生组号
         */
        foreach ($students as $name => $r){
            if (isset($count[$r + 1])){
                $count[$r + 1]++;
            } else {
                $count[$r + 1] = 1;
            }
        }

        $groupCount = count($count); // 总共有多少个组

        /**
         * 将频率转化为索引
         * @var int $r 学生组号
         */
        for ($r = 0; $r < $groupCount - 1; $r++){
            if (!isset($count[$r])){
                $count[$r] = 0;
            }
            $count[$r + 1] += $count[$r];
        }

        $aux = [];
        foreach ($students as $name => $r){
            $aux[$count[$r]] = $name;
            $count[$r]++;
        }

        print_r($aux);
    }
}
