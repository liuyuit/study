<?php
// 将 ascii 码转化为字符
echo chr(12); //
echo "\r\n";
// 将字符转化为 ascii 码
echo 


exit;
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
        $studentNumber = count($students);

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
        for ($r = 0; $r < $groupCount; $r++){
            if (!isset($count[$r])){
                $count[$r] = 0;
            }
            if (!isset($count[$r + 1])){
                $count[$r + 1] = 0;
            }
            $count[$r + 1] += $count[$r];
        }

        /**
         * 数据分类，将学生名字与他排序后的序号对应起来
         * @var array $aux 排序后学生的序号 => 学生名字
         */
        $aux = [];
        foreach ($students as $name => $r){
            $aux[$count[$r]] = $name;
            $count[$r]++;
        }

        /**
         * 将学生按顺序放入到数组中
         * @var array $orderedStudents 学生序号 => 学生名字，最终的排序后的学生数组
         */
        $orderedStudents = [];
        for($i = 0; $i < $studentNumber; $i++){
            $orderedStudents[$aux[$i]] = $students[$aux[$i]];
        }

        print_r($orderedStudents);
    }
}
