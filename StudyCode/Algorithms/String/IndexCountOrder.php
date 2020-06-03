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
         * @var int $r 学生组号
         */
        foreach ($students as $name => $r){
                        
        }
    }
}
